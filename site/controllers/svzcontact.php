<?php
use Uniform\Form;

/**
 * Contact form of the Sportvereinszentrum (SVZ).
 *
 * The recipient is configured in the config.php (option sfb.svz.recipient), see SvzMailAction.
 */
return function ($site, $pages, $page) {
    $packages = array_keys(SvzPage::PACKAGES);
    $today = date('Y-m-d');

    $form = new Form([
        'vorname' => [
            'rules' => ['required', 'maxLength' => 100],
            'message' => 'Bitte geben Sie Ihren Vornamen an.',
        ],
        'nachname' => [
            'rules' => ['required', 'maxLength' => 100],
            'message' => 'Bitte geben Sie Ihren Nachnamen an.',
        ],
        'email' => [
            'rules' => ['required', 'email', 'maxLength' => 200],
            'message' => 'Bitte geben Sie eine gültige E-Mail Adresse an.',
        ],
        'telefon' => [
            'rules' => ['match' => '/^[0-9 +\-\/().]{5,30}$/'],
            'message' => 'Bitte geben Sie eine gültige Telefonnummer an.',
        ],
        'datum' => [
            'rules' => ['required', 'match' => '/^\d{4}-\d{2}-\d{2}$/', 'date', 'date' => ['>=', $today]],
            // one message per rule (same order as the rules above)
            'message' => [
                'Bitte wählen Sie ein Datum aus.',
                'Bitte wählen Sie ein gültiges Datum aus.',
                'Bitte wählen Sie ein gültiges Datum aus.',
                'Das Datum darf nicht in der Vergangenheit liegen.',
            ],
        ],
        'paket' => [
            'rules' => ['required', 'in' => [$packages]],
            'message' => 'Bitte entscheiden Sie sich für ein Paket',
        ],
        'mitglied' => [
            'rules' => ['required', 'in' => [['ja', 'nein']]],
            'message' => 'Bitte geben Sie an, ob Sie aktives Mitglied sind.',
        ],
        'servicekraft' => [
            'rules' => ['required', 'in' => [['ja', 'nein']]],
            'message' => 'Bitte geben Sie an, ob Sie eine Servicekraft wünschen.',
        ],
        'nachricht' => [
            'rules' => ['required', 'maxLength' => 5000],
            'message' => 'Bitte geben Sie eine Nachricht ein.',
        ],
        'dataDeclaration' => [
            'rules' => ['required'],
            'message' => 'Bitte bestätigen Sie die Einwilligung zur Nutzung Ihrer personenbezogenen Daten.',
        ],
    ]);

    if (r::is('POST')) {
        $form->guard()
            ->turnstileGuard(['action' => 'svz-contact'])
            ->svzMailAction()
            ->done();
    }

    // date which is preselected via ?datum=YYYY-MM-DD (button "Den xx.xx.xxxx direkt anfragen" of the calendar)
    $presetDate = '';
    $requested = (string) get('datum', '');
    $parsed = DateTimeImmutable::createFromFormat('!Y-m-d', $requested);

    if ($parsed !== false && $parsed->format('Y-m-d') === $requested && $requested >= $today) {
        $presetDate = $requested;
    }

    return [
        'form' => $form,
        'packages' => SvzPage::PACKAGES,
        'minDate' => $today,
        'presetDate' => $presetDate,
    ];
};
