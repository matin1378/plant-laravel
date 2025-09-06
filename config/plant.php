<?php
return [
    'api_key'  => env('PLANT_API_KEY', 'کلید-API-خودت'),
    'endpoint' => env('PLANT_API_ENDPOINT', 'https://api.metisai.ir/api/v1/wrapper/openai_chat_completion/chat/completions'),
    'key_map' => [
        'نام فارسی' => ['نام‌ فارسی','نام‌فارسی','persian name','persian_name','fa name'],
        'نام علمی'  => ['نام‌ علمی','نام‌علمی','scientific name','scientific_name','latin name'],
        'گروه گیاهی'=> ['نوع گیاه','plant type','group','گروه'],
        'شرایط نگهداری'=> ['care','care instructions','مراقبت'],
        'نیاز آبی'   => ['water need','watering','water requirement','نیاز به آب'],
        'نیاز نوری'  => ['light need','light requirement','نور مورد نیاز','نیاز به نور'],
        'وضعیت فعلی' => ['status','current condition','حالت فعلی','وضعیت'],
        'کود پیشنهادی'=> ['fertilizer','fertilizer recommendation','کود'],
    ],
    'candidates' => [
        'Dracaena fragrans','Dracaena marginata','Monstera deliciosa','Epipremnum aureum',
        'Spathiphyllum wallisii','Ficus elastica','Ficus benjamina','Sansevieria trifasciata',
        'Zamioculcas zamiifolia','Chlorophytum comosum','Philodendron hederaceum',
        'Dieffenbachia seguine','Schefflera arboricola','Aglaonema commutatum',
        'Calathea ornata','Nephrolepis exaltata','Aloe vera','Haworthia attenuata',
        'Pilea peperomioides','Tradescantia zebrina','Anthurium andraeanum'
    ],
];
