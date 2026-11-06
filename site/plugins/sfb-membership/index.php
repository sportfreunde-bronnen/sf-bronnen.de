<?php

require_once __DIR__ . '/src/MembershipApplication.php';
require_once __DIR__ . '/src/MembershipPdf.php';

// The action extends the PhpMailerAction of the plugin uniform-actions, which is loaded after this plugin.
// Therefore the class is loaded on demand: Uniform checks with class_exists() before it runs an action.
spl_autoload_register(function (string $class): void {
    if ($class === 'Uniform\\Actions\\MembershipMailAction') {
        require_once __DIR__ . '/src/MembershipMailAction.php';
    }
});

Kirby::plugin('sfb/membership', [
    'options' => [
        // e-mail address of the membership administration which receives the applications (page 'Mitglied werden').
        // Set 'sfb.membership.recipient' in the (gitignored) site/config/config.php. As long as it is empty the form rejects every submission.
        'recipient' => null,
        // path of the PDF form which is filled in. Empty: data/beitrittserklarung_stand_03.2026.pdf in the project folder
        'template' => null,
    ],
    // rules of the form fields, see site/controllers/membership.php
    'validators' => [
        'membershipGroups' => fn ($value) => MembershipApplication::validGroups($value),
        'membershipPassive' => fn ($value) => MembershipApplication::validPassiveExclusive($value),
        'membershipFamily' => fn ($value) => MembershipApplication::validFamily($value),
        'membershipIban' => fn ($value) => MembershipApplication::validIban($value),
        'membershipBic' => fn ($value) => MembershipApplication::validBic($value),
        'membershipEntryDate' => fn ($value) => MembershipApplication::isEntryDate($value),
        'membershipBirthDate' => fn ($value) => MembershipApplication::isBirthDate($value),
    ],
]);
