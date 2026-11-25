<?php

require_once __DIR__ . '/src/RolePageAccess.php';
require_once __DIR__ . '/src/RolePagePermissions.php';
require_once __DIR__ . '/src/RolePageGuards.php';
require_once __DIR__ . '/src/RoleAwarePage.php';

// Pages are protected by their guards (RoleAwarePage, RolePageGuards).
// Kirby knows no file models: all changes of files of a restricted page are denied by hooks
$guardFile = function ($file) {
    RolePageAccess::guardFile($file);
};

Kirby::plugin('sfb/roles', [
    'pageModels' => [
        // all pages without an own model are role aware, the own models extend RoleAwarePage
        'default' => 'RoleAwarePage',
    ],
    'hooks' => [
        'file.create:before'         => $guardFile,
        'file.replace:before'        => $guardFile,
        'file.update:before'         => $guardFile,
        'file.delete:before'         => $guardFile,
        'file.changeName:before'     => $guardFile,
        'file.changeSort:before'     => $guardFile,
        'file.changeTemplate:before' => $guardFile,
        // the rules are part of the site content
        'site.update:after' => function () {
            RolePageAccess::reset();
        },
    ],
]);
