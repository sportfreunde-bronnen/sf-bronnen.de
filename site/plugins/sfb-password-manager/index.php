<?php

require_once __DIR__ . '/src/PasswordManagerIgnore.php';

Kirby::plugin('sfb/password-manager', [
    'hooks' => [
        // runs before the page cache, so cached pages contain the attributes as well
        'page.render:after' => function (string $contentType, string $html): string {
            if ($contentType !== 'html') {
                return $html;
            }

            return PasswordManagerIgnore::apply($html);
        },
    ],
]);
