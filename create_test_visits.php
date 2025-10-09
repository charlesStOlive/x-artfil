<?php

use Shetabit\Visitor\Models\Visit;
use Carbon\Carbon;

// Créer quelques visites de test
$visits = [
    ['/', 'desktop', 'Windows', 'Chrome'],
    ['/contact', 'mobile', 'iOS', 'Safari'],
    ['/', 'desktop', 'macOS', 'Safari'],
    ['/about', 'tablet', 'Android', 'Chrome'],
    ['/contact', 'mobile', 'Android', 'Chrome'],
    ['/', 'desktop', 'Windows', 'Firefox'],
    ['/services', 'desktop', 'Linux', 'Chrome'],
];

foreach ($visits as $visit) {
    Visit::create([
        'ip' => '192.168.1.' . rand(1, 254),
        'method' => 'GET',
        'request' => $visit[0],
        'url' => 'https://x-artfil.test' . $visit[0],
        'referer' => null,
        'languages' => 'fr-FR,fr;q=0.9,en;q=0.8',
        'useragent' => 'Mozilla/5.0 (compatible; Test)',
        'headers' => '{}',
        'device' => $visit[1],
        'platform' => $visit[2],
        'browser' => $visit[3],
        'created_at' => Carbon::now()->subMinutes(rand(1, 1440)), // Dans les dernières 24h
    ]);
}

echo "✅ " . count($visits) . " visites de test créées !\n";