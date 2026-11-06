<?php

require_once __DIR__ . '/src/SvzDomain.php';

Kirby::plugin('sfb/svz', [
    'options' => [
        // host name of the SVZ site (without scheme and port), set 'sfb.svz.host' in the (gitignored)
        // site/config/config.php for other environments, e.g. 'svz.sf-bronnen.de.test' for local development.
        // An empty value disables the own domain, the SVZ page is then served below the main domain as usual.
        'host' => 'svz.sf-bronnen.de',
        // URL of the main site, used for all links which leave the SVZ site (header, footer, contact form).
        // Default: the SVZ host without its first label (svz.sf-bronnen.de -> sf-bronnen.de)
        'mainUrl' => null,
        // id of the SVZ page
        'page' => 'gesamtverein/allgemeines/svz',
        // e-mail address which receives the requests of the SVZ contact form (subpage 'anfrage' of the SVZ page).
        // Set 'sfb.svz.recipient' in the (gitignored) site/config/config.php. As long as it is empty the form rejects every submission.
        'recipient' => null,
    ],
    'routes' => [
        [
            'pattern' => '(:all)',
            'method'  => 'ALL',
            'action'  => function ($path = null) {
                return SvzDomain::route($path);
            },
        ],
    ],
]);
