<?php

Kirby::plugin('sf-bronnen/layout-block', [
  'blueprints' => [
    'blocks/layout' => __DIR__ . '/blueprints/blocks/layout.yml'
  ],
  'snippets' => [
    'blocks/layout' => __DIR__ . '/snippets/blocks/layout.php'
  ]
]);