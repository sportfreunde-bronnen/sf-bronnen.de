<?php

include __DIR__ . '/../kirby/bootstrap.php';

define('EXTRACT_CONTENT', (bool)($_GET['extractContent'] ?? false));
define('APPEND_TITLE', $_GET['appendTitle'] ?? '');

$kirby = new Kirby([
    'roots' => [
        'index'    => __DIR__,
        'base'     => $base    = dirname(__DIR__),
        'content'  => $base . '/content',
        'site'     => $base . '/site',
        'storage'  => $storage = $base . '/storage',
        'accounts' => $storage . '/accounts',
        'cache'    => $storage . '/cache',
        'sessions' => $storage . '/sessions',
    ]
]);

echo $kirby->render();