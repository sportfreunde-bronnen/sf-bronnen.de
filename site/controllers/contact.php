<?php
use Uniform\Form;
return function ($site, $pages, $page) {
    $form = new Form([
        'vorname' => [
            'rules' => ['required'],
            'message' => 'Bitte geben Sie Ihren Namen an.',
        ],
        'nachname' => [],
        'empfaenger' => [
            'rules' => ['required'],
            'message' => 'Bitte geben Sie Ihre Telefonnummer an.',
        ],
        'email' => [
            'rules' => ['required', 'email'],
            'message' => 'Bitte geben Sie eine gültige E-Mail Adresse an.',
        ],
        'nachricht' => [
            'rules' => ['required'],
            'message' => 'Bitte geben Sie eine Anfrage ein.'
        ]
    ]);
    if (r::is('POST')) {
        $form->phpMailerAction([
            'to' => 'me@example.com',
            'from' => 'info@example.com',
        ]);
    }
    return compact('form');
};