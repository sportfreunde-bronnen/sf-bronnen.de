<?php

include __DIR__ . '/../kirby/bootstrap.php';

$template = $_SERVER['HTTP_HOST'] == 'dev.sfb:1949' ? 'V2' : '';

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
        'templates' => $base . '/site/templates' . $template,
        'snippets' => $base . '/site/snippets' . $template,
    ]
]);

echo $kirby->render();