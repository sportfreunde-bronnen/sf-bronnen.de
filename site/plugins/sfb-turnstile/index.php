<?php

require_once __DIR__ . '/src/TurnstileGuard.php';

Kirby::plugin('sfb/turnstile', [
    'options' => [
        // public site key of the Turnstile widget
        'sitekey' => '0x4AAAAAAF09InnVGtIfCAqo',
        // secret key of the widget: set 'sfb.turnstile.secret' in the (gitignored) site/config/config.php
        'secret' => null,
        // hostnames of the frontend accepted in the siteverify response
        // (add 'localhost' for development in the local config.php only)
        'hostnames' => ['sf-bronnen.de', 'www.sf-bronnen.de', 'sf-bronnen.de.test'],
    ],
    'snippets' => [
        'turnstile' => __DIR__ . '/snippets/turnstile.php',
    ],
]);
