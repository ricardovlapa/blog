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
        'description' => 'Crónicas e textos de opinião sobre o Barreiro, a sociedade e o tempo em que vivemos.',
        'baseUrl' => 'ricardovenceslaulapa.com',
        'allowedHosts' => [
            'ricardovenceslaulapa.com',
            'www.ricardovenceslaulapa.com',
        ],
        'socialImage' => 'https://ricardovenceslaulapa.com/assets/images/socialImage.jpg',
        'adSlotsVisible' => $adSlotsVisible,
    ],
    'postsData' => __DIR__ . '/Data/posts.json',
    'tagsData' => __DIR__ . '/Data/tags.json',
];
