<?php
use Uniform\Form;

/**
 * Digital membership application ("Mitglied werden").
 *
 * After the submission the PDF form data/beitrittserklarung_stand_03.2026.pdf is filled in and sent
 * to the membership administration. The recipient is configured in the config.php (option
 * sfb.membership.recipient), see plugin sfb-membership (MembershipMailAction, MembershipPdf).
 */
return function ($site, $pages, $page) {
    $today = date('Y-m-d');

    $form = new Form([
        'nachname' => [
            'rules' => ['required', 'maxLength' => 100],
            'message' => 'Bitte geben Sie Ihren Nachnamen an.',
        ],
        'vorname' => [
            'rules' => ['required', 'maxLength' => 100],
            'message' => 'Bitte geben Sie Ihren Vornamen an.',
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
            'message' => 'Bitte geben Sie Ihren Wohnort an.',
        ],
        'eintrittsdatum' => [
            'rules' => ['required', 'membershipEntryDate'],
            // one message per rule (same order as the rules above)
            'message' => [
                'Bitte geben Sie das gewünschte Eintrittsdatum an.',
                'Das Eintrittsdatum darf nicht in der Vergangenheit und höchstens ein Jahr in der Zukunft liegen.',
            ],
        ],
        'geburtsdatum' => [
            'rules' => ['required', 'membershipBirthDate'],
            'message' => [
                'Bitte geben Sie Ihr Geburtsdatum an.',
                'Bitte geben Sie ein gültiges Geburtsdatum an.',
            ],
        ],
        'email' => [
            'rules' => ['required', 'email', 'maxLength' => 200],
            'message' => 'Bitte geben Sie eine gültige E-Mail Adresse an.',
        ],
        'telefon' => [
            'rules' => ['required', 'match' => '/^[0-9 +\-\/().]{5,30}$/'],
            'message' => 'Bitte geben Sie eine gültige Telefonnummer an.',
        ],
        'gruppen' => [
            'rules' => ['required', 'membershipGroups', 'membershipPassive'],
            'message' => [
                'Bitte wählen Sie eine Gruppe bzw. Abteilung oder die passive Mitgliedschaft.',
                'Bitte wählen Sie nur Gruppen bzw. Abteilungen aus der Liste.',
                'Die passive Mitgliedschaft kann nicht mit einer Gruppe kombiniert werden.',
            ],
        ],
        'beitragsklasse' => [
            'rules' => ['required', 'in' => [array_keys(MembershipApplication::FEE_CLASSES)]],
            'message' => 'Bitte wählen Sie eine Beitragsklasse.',
        ],
        'familie' => [
            'rules' => ['membershipFamily'],
            'message' => 'Bitte geben Sie bei jedem weiteren Familienmitglied Nachnamen, Vornamen, Geburtsdatum und die Gruppe bzw. Abteilung an.',
        ],
        'iban' => [
            'rules' => ['required', 'membershipIban'],
            'message' => [
                'Bitte geben Sie die IBAN an.',
                'Bitte geben Sie eine gültige IBAN an.',
            ],
        ],
        'bic' => [
            'rules' => ['required', 'membershipBic'],
            'message' => [
                'Bitte geben Sie die BIC an.',
                'Bitte geben Sie eine gültige BIC an.',
            ],
        ],
        'kreditinstitut' => [
            'rules' => ['required', 'maxLength' => 100],
            'message' => 'Bitte geben Sie das Kreditinstitut an.',
        ],
        'kontoinhaberNachname' => [
            'rules' => ['required', 'maxLength' => 100],
            'message' => 'Bitte geben Sie den Nachnamen des Kontoinhabers an.',
        ],
        'kontoinhaberVorname' => [
            'rules' => ['required', 'maxLength' => 100],
            'message' => 'Bitte geben Sie den Vornamen des Kontoinhabers an.',
        ],
        'dataDeclaration' => [
            'rules' => ['required'],
            'message' => 'Bitte bestätigen Sie die Einwilligung zur Erhebung und Verarbeitung Ihrer personenbezogenen Daten.',
        ],
    ]);

    if (r::is('POST')) {
        // clean up the input before it is validated: IBAN and BIC without spaces in upper case,
        // empty rows of the family members removed (family members only belong to the family fee)
        $form->data('iban', MembershipApplication::normalizeIban((string) $form->data('iban', '', false)));
        $form->data('bic', MembershipApplication::normalizeBic((string) $form->data('bic', '', false)));
        $form->data('familie', $form->data('beitragsklasse', '', false) === 'familie'
            ? MembershipApplication::cleanFamily($form->data('familie', '', false))
            : []);

        $form->guard()
            ->turnstileGuard(['action' => 'membership'])
            ->membershipMailAction()
            ->done();
    }

    return [
        'form' => $form,
        'departments' => MembershipApplication::DEPARTMENTS,
        'feeClasses' => MembershipApplication::FEE_CLASSES,
        'maxFamilyMembers' => MembershipApplication::MAX_FAMILY_MEMBERS,
        'today' => $today,
    ];
};
