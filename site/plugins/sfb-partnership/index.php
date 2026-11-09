<?php

require_once __DIR__ . '/src/PartnershipRequest.php';

// The action extends the PhpMailerAction of the plugin uniform-actions, which is loaded after this plugin.
// Therefore the class is loaded on demand: Uniform checks with class_exists() before it runs an action.
spl_autoload_register(function (string $class): void {
    if ($class === 'Uniform\\Actions\\PartnershipMailAction') {
        require_once __DIR__ . '/src/PartnershipMailAction.php';
    }
});

Kirby::plugin('sfb/partnership', [
    'options' => [
        // e-mail address which receives the partnership requests (page 'Partnerschaft' below 'Förderverein').
        // Set 'sfb.partnership.recipient' in the (gitignored) site/config/config.php. As long as it is empty the form rejects every submission.
        'recipient' => null,
    ],
    // rules of the form fields, see site/controllers/partnership.php
    'validators' => [
        'partnershipInterests' => fn ($value) => PartnershipRequest::validInterests($value),
    ],
]);
