<?php
$appEnv = strtolower((string) getenv('APP_ENV'));
$adSlotsVisible = $appEnv !== 'production';
$adSlotsEnv = getenv('AD_SLOTS_VISIBLE');
if ($adSlotsEnv !== false) {
    $adSlotsVisible = filter_var($adSlotsEnv, FILTER_VALIDATE_BOOLEAN);
}

return [
    'site' => [
        'title' => 'Ricardo Venceslau Lapa',
        'tagline' => 'Pensamentos, notas e reflexões sobre o tempo em que vivemos.',
        'description' => 'Crónicas de Ricardo Venceslau Lapa sobre o Barreiro, sociedade e política local.',
        'baseUrl' => 'https://ricardovenceslaulapa.pt',
        'allowedHosts' => [
            'ricardovenceslaulapa.pt',
            'www.ricardovenceslaulapa.pt',
        ],
        'socialImage' => 'https://ricardovenceslaulapa.pt/assets/images/socialImage.jpg',
        'adSlotsVisible' => $adSlotsVisible,
    ],
    'postsData' => __DIR__ . '/Data/posts.json',
    'tagsData' => __DIR__ . '/Data/tags.json',
];
