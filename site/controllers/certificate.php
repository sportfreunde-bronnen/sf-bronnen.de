<?php
use Uniform\Form;
return function ($site, $pages, $page) {
    $form = new Form([
        'email' => [
            'rules' => ['required', 'email'],
            'message' => 'Bitte geben Sie eine gültige E-Mail Adresse an.',
        ],
        'address' => [
            'rules' => ['required'],
            'message' => 'Bitte geben Sie eine Adresse ein.'
        ],
        'dataDeclaration' => [
            'rules' => ['required'],
            'message' => 'Bitte bestätigen Sie die Einwilligung zur Nutzung Ihrer personenbezogenen Daten'
        ]
    ]);
    if (r::is('POST')) {
        $form->certificateAction([]);
    }
    return compact('form');
};