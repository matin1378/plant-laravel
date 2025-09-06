<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PlantAnalyzer
{
    private string $apiKey;
    private string $endpoint;
    private string $cacheDir = 'plant_cache';
    private string $cacheSalt = 'v4-key-normalize-retry-laravel';
    private int $maxBytes = 5 * 1024 * 1024;
    private int $maxSide = 1280;

    public function __construct()
    {
        $this->apiKey   = config('plant.api_key');
        $this->endpoint = config('plant.endpoint');
        Storage::disk('local')->makeDirectory($this->cacheDir);
    }

    public function hashBytes(string $bytes): string
    {
        return hash('sha256', $this->cacheSalt . $bytes);
    }

    public function loadCache(string $hash): ?array
    {
        $path = $this->cacheDir . "/{$hash}.json";
        if (Storage::disk('local')->exists($path)) {
            return json_decode(Storage::disk('local')->get($path), true);
        }
        return null;
    }

    public function saveCache(string $hash, array $data): void
    {
        $path = $this->cacheDir . "/{$hash}.json";
        Storage::disk('local')->put($path, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    public function analyzeBytes(string $bytes): array
    {
        if (strlen($bytes) > $this->maxBytes) $bytes = $this->compress($bytes);
        $b64 = base64_encode($bytes);

        $messages1 = [[
            'role' => 'user',
            'content' => [
                ['type'=>'text','text'=>$this->promptMain()],
                ['type'=>'image_url','image_url'=>['url'=>"data:image/jpeg;base64,{$b64}"]]
            ]
        ]];
        $text1 = $this->callApi($messages1);
        $parsed = $this->formatAiResponse($text1);

        if ($this->isNonPlant($text1, $parsed)) {
            return ['تحلیل کلی' => trim($text1) !== '' ? trim($text1) : 'این تصویر مربوط به گیاه نیست.'];
        }

        if (!$this->filled($parsed,'نام فارسی') && !$this->filled($parsed,'نام علمی')) {
            $parsed2 = $this->retryIdentification($b64);
            foreach (['نام فارسی','نام علمی','گروه گیاهی','وضعیت فعلی'] as $k) {
                if ($this->filled($parsed2,$k)) $parsed[$k] = $parsed2[$k];
            }
        }

        $parsed = $this->normalizeKeys($parsed);
        $required = ['نام فارسی','نام علمی','گروه گیاهی','شرایط نگهداری','نیاز آبی','نیاز نوری','وضعیت فعلی','کود پیشنهادی'];
        foreach ($required as $k) if (!$this->filled($parsed,$k)) $parsed[$k] = 'نامعلوم';

        $statusText = mb_strtolower(($parsed['وضعیت فعلی'] ?? '') . ' ' . ($parsed['گروه گیاهی'] ?? ''));
        if ($this->containsAny($statusText, ['تنش','آسیب','زرد','کلروز','خشک','wilting','stress','tip burn','leaf burn','سوختگی'])) {
            $parsed['کود پیشنهادی'] = 'کود۱';
        } elseif ($this->containsAny($statusText, ['گل','flower','bloom'])) {
            $parsed['کود پیشنهادی'] = 'کود۳';
        } else {
            $parsed['کود پیشنهادی'] = 'کود۲';
        }

        return $parsed;
    }

    private function promptMain(): string
    {
        return <<<TXT
تصویر را بررسی کن. اگر «گیاه» است، فقط با همین کلیدها و در قالب «کلید: مقدار» بده:
نام فارسی: ...
نام علمی: ...
گروه گیاهی: ...
وضعیت فعلی: ...
شرایط نگهداری: ...
نیاز آبی: ...
نیاز نوری: ...
کود پیشنهادی: ...
اگر گیاه نیست یا نامشخص است، به‌جای این‌ها فقط یک پاراگراف «تحلیل کلی» بده و توضیح بده تصویر چیست.
TXT;
    }

    private function compress(string $bytes): string
    {
        $manager = new ImageManager(new Driver());
        $img = $manager->read($bytes)->toRgb();
        $w = $img->width(); $h = $img->height();
        $side = max($w,$h);
        if ($side > $this->maxSide) {
            $scale = $this->maxSide / $side;
            $img = $img->scale(width: intval($w*$scale), height: intval($h*$scale));
        }
        return (string)$img->toJpeg(quality:72);
    }

    private function callApi(array $messages, int $maxTokens=600, int $temperature=0): string
    {
        $res = Http::withToken($this->apiKey)->timeout(60)->post(config('plant.endpoint'), [
            'model' => 'gpt-4o-mini',
            'temperature' => $temperature,
            'messages' => $messages,
            'max_tokens' => $maxTokens,
        ]);
        if (!$res->ok()) {
            $msg = $res->json('error.message') ?? $res->body();
            throw new \RuntimeException("API error: {$msg}");
        }
        return $res->json('choices.0.message.content') ?? '';
    }

    private function retryIdentification(string $b64): array
    {
        $candidates = config('plant.candidates');
        $prompt = "نزدیک‌ترین گزینه به تصویر را از فهرست زیر انتخاب کن و فقط JSON فارسی با این کلیدها بده.
"
                . "اگر هیچ گزینه خیلی نزدیک نبود، بهترین حدس علمی خودت را بنویس و بقیه کلیدها را پر کن.

"
                . "فهرست: " . implode(', ',$candidates) . "

"
                . "خروجی دقیقاً JSON با کلیدها:\n{\n  \"نام فارسی\": \"\",\n  \"نام علمی\": \"\",\n  \"گروه گیاهی\": \"\",\n  \"وضعیت فعلی\": \"\"\n}\nهیچ متن اضافه ننویس.";

        $messages = [
            ['role'=>'system','content'=>[['type'=>'text','text'=>'پاسخ دقیق و کوتاه. فقط JSON فارسی با کلیدهای مشخص‌شده.']]],
            ['role'=>'user','content'=>[
                ['type'=>'text','text'=>$prompt],
                ['type'=>'image_url','image_url'=>['url'=>"data:image/jpeg;base64,{$b64}"]],
            ]],
        ];
        try {
            $txt = $this->callApi($messages, 350, 0);
            return $this->formatAiResponse($txt);
        } catch (\Throwable $e) { return []; }
    }

    private function tryExtractJson(string $text): ?array
    {
        if (preg_match('/\{.*\}/s', $text, $m)) {
            $obj = json_decode($m[0], true);
            return is_array($obj) ? $obj : null;
        }
        return null;
    }

    private function formatAiResponse(string $text): array
    {
        if ($j = $this->tryExtractJson($text)) {
            $norm = [];
            foreach ($j as $k=>$v) $norm[(string)$k] = is_null($v) ? '' : (string)$v;
            return $this->normalizeKeys($norm);
        }
        $result = [];
        foreach (preg_split('/\r?\n/', $text) as $line) {
            $line = trim($line);
            if ($line === '') continue;
            if (preg_match('/^([^:：]+)\s*[:：]\s*(.*)$/u', $line, $m)) {
                $result[trim($m[1])] = trim($m[2]); continue;
            }
            if (preg_match('/^([^؛]+)\s*；?\s*(.*)$/u', $line, $m)) {
                $result[trim($m[1])] = trim($m[2]); continue;
            }
        }
        if (empty($result)) return ['تحلیل کلی'=>trim($text)];
        return $this->normalizeKeys($result);
    }

    private function normalizeKeys(array $data): array
    {
        $map = config('plant.key_map');
        $out = [];
        foreach ($data as $k=>$v) {
            $nk = $this->canonKey($k, $map);
            $out[$nk ?? $k] = $v;
        }
        return $out;
    }

    private function canonKey(string $key, array $map): ?string
    {
        $nk = $this->normalizeStr($key);
        foreach ($map as $canon=>$aliases) {
            $pool = array_merge([$canon], $aliases);
            foreach ($pool as $a) if ($this->normalizeStr($a) === $nk) return $canon;
        }
        return null;
    }

    private function normalizeStr(string $s): string
    {
        $s = normalizer_normalize($s, \Normalizer::FORM_KC) ?: $s;
        $s = str_replace("\u{200C}", ' ', $s);
        $s = preg_replace('/\s+/u', ' ', $s);
        return mb_strtolower(trim($s));
    }

    private function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $n) if (mb_stripos($haystack, $n) !== false) return true;
        return false;
    }

    private function filled(array $arr, string $key): bool
    {
        return array_key_exists($key, $arr) && trim((string)$arr[$key]) !== '';
    }

    private function isNonPlant(string $txt, array $res): bool
    {
        $main = ['نام فارسی','نام علمی','گروه گیاهی','وضعیت فعلی'];
        $allEmpty = true;
        foreach ($main as $m) { if ($this->filled($res,$m)) { $allEmpty=false; break; } }
        if ($allEmpty) return true;

        $t = mb_strtolower($txt);
        $non = ['packet','pack','brand','barcode','nutrition facts','snack','chips',
                'beans','lentil','pulses','product','logo','package',
                'بسته','پاکت','محصول','برند','لوگو','بارکد','حبوبات','عدس','نخود','لوبیا'];
        $plant = ['plant','leaf','leaves','flower','stem','soil','pot','گیاه','برگ','ساقه','گل','گلدان','خاک'];
        return ($this->containsAny($t,$non) && !$this->containsAny($t,$plant));
    }
}
