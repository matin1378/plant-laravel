<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\PlantAnalyzer;

class PlantAnalyzeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function analyze(Request $request, PlantAnalyzer $analyzer)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'هیچ فایلی ارسال نشده است!'], 400);
        }
        $file = $request->file('file');
        if (!$file->isValid()) {
            return response()->json(['error' => 'فایل معتبر نیست.'], 400);
        }
        $bytes = file_get_contents($file->getRealPath());
        if (!$bytes) {
            return response()->json(['error' => 'محتوای فایل خالی است!'], 400);
        }

        $hash = $analyzer->hashBytes($bytes);
        if ($cached = $analyzer->loadCache($hash)) {
            return response()->json(['result' => $cached, 'cached' => true]);
        }

        $safeName = now()->format('Ymd_His_') . preg_replace('/[^\w\.\-]+/u', '_', $file->getClientOriginalName());
        Storage::disk('public')->put("uploads/{$safeName}", $bytes);

        $result = $analyzer->analyzeBytes($bytes);
        $analyzer->saveCache($hash, $result);

        return response()->json(['result' => $result, 'cached' => false]);
    }
}
