<?php

require_once __DIR__ . '/src/SvzDomain.php';
require_once __DIR__ . '/src/SvzFloorplan.php';

Kirby::plugin('sfb/svz', [
    'options' => [
        // host name of the SVZ site (without scheme and port), set 'sfb.svz.host' in the (gitignored)
        // site/config/config.php for other environments, e.g. 'vermietung.sf-bronnen.de.test' for local development.
        // An empty value disables the own domain, the SVZ page is then served below the main domain as usual.
        'host' => 'vermietung.sf-bronnen.de',
        // URL of the main site, used for all links which leave the SVZ site (header, footer, contact form).
        // Default: the SVZ host without its first label (vermietung.sf-bronnen.de -> sf-bronnen.de)
        'mainUrl' => null,
        // id of the SVZ page
        'page' => 'gesamtverein/allgemeines/svz',
        // e-mail address which receives the requests of the SVZ contact form (subpage 'anfrage' of the SVZ page).
        // Set 'sfb.svz.recipient' in the (gitignored) site/config/config.php. As long as it is empty the form rejects every submission.
        'recipient' => null,
    ],
    // Kirby only looks up templates directly in site/templates, so the templates grouped in site/templates/svz are registered here
    'templates' => [
        'svz'         => dirname(__DIR__, 2) . '/templates/svz/svz.php',
        'svzcalendar' => dirname(__DIR__, 2) . '/templates/svz/svzcalendar.php',
        'svzcontact'  => dirname(__DIR__, 2) . '/templates/svz/svzcontact.php',
        'svzplan'     => dirname(__DIR__, 2) . '/templates/svz/svzplan.php',
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
