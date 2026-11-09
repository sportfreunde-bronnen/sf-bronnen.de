<?php
use Uniform\Form;

/**
 * Digital partnership request of the Förderverein ("Partnerschaft"), replaces the PDF form
 * "Vordruck Werbemöglichkeiten Förderverein Sportfreunde Bronnen e.V.".
 *
 * The request is sent to the e-mail address configured in the config.php (option
 * sfb.partnership.recipient), see plugin sfb-partnership (PartnershipMailAction, PartnershipRequest).
 */
return function ($site, $pages, $page) {
    $form = new Form([
        'interessen' => [
            'rules' => ['required', 'partnershipInterests'],
            // one message per rule (same order as the rules above)
            'message' => [
                'Bitte wählen Sie mindestens einen Bereich aus, an dem Sie Interesse haben.',
                'Bitte wählen Sie nur Bereiche aus der Liste.',
            ],
        ],
        'firma' => [
            'rules' => ['maxLength' => 150],
            'message' => 'Der Firmenname ist zu lang.',
        ],
        'ansprechpartner' => [
            'rules' => ['required', 'maxLength' => 150],
            'message' => 'Bitte geben Sie einen Ansprechpartner an.',
        ],
        'strasse' => [
            'rules' => ['required', 'maxLength' => 100],
            'message' => 'Bitte geben Sie Straße und Hausnummer an.',
        ],
        'plz' => [
            'rules' => ['required', 'match' => '/^\d{5}$/'],
            'message' => 'Bitte geben Sie eine fünfstellige Postleitzahl an.',
        ],
        'ort' => [
            'rules' => ['required', 'maxLength' => 100],
            'message' => 'Bitte geben Sie den Ort an.',
        ],
        'telefon' => [
            'rules' => ['match' => '/^[0-9 +\-\/().]{5,30}$/'],
            'message' => 'Bitte geben Sie eine gültige Telefonnummer an.',
        ],
        'email' => [
            'rules' => ['required', 'email', 'maxLength' => 200],
            'message' => 'Bitte geben Sie eine gültige E-Mail Adresse an.',
        ],
        'nachricht' => [
            'rules' => ['maxLength' => 5000],
            'message' => 'Die Anmerkungen sind zu lang (höchstens 5000 Zeichen).',
        ],
        'dataDeclaration' => [
            'rules' => ['required'],
            'message' => 'Bitte bestätigen Sie die Einwilligung zur Verarbeitung Ihrer personenbezogenen Daten.',
        ],
    ]);

    if (r::is('POST')) {
        $form->guard()
            ->turnstileGuard(['action' => 'partnership'])
            ->partnershipMailAction()
            ->done();
    }

    return [
        'form' => $form,
        'interests' => PartnershipRequest::INTERESTS,
    ];
};
