<?php

ini_set('display_errors', '0');

define('EXTRACT_CONTENT', (bool)($_GET['extractContent'] ?? false));
define('APPEND_TITLE', $_GET['appendTitle'] ?? '');
define('KIRBY_HELPER_DUMP', false);

include __DIR__ . '/../kirby/bootstrap.php';

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