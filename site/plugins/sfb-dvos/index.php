<?php

require_once __DIR__ . '/src/DvosPlayerStatistics.php';

Kirby::plugin('sfb/dvos', [
    'options' => [
        // page of the DVOS (Dartverband Oberschwaben) which is requested (POST) with the id of the club
        'url' => 'https://www.dvos.de/ligaauswertung.php?seite=0',
        // seconds until a request to the DVOS is aborted
        'timeout' => 10,
        // lifetime of the prepared statistics in the cache
        'cacheMinutes' => 12 * 60,
        // Kirby cache of the prepared statistics, switch it off with 'sfb.dvos.cache.playerstatistics' => false
        'cache' => [
            'playerstatistics' => true,
        ],
    ],
]);
