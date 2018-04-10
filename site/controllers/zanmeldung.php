<?php

function addToStructure($p, $field, $data = array()) {
    $fieldData = $p->$field()->yaml();
    $fieldData[] = $data;
    $fieldData = yaml::encode($fieldData);
    $p->update(array(
        $field => $fieldData
    ));
}

return function($site, $pages, $page) {

    $alert = null;

    if(r::is('post')) {

        if(!empty(get('website'))) {
            // lets tell the bot that everything is ok
            go($page->url());
            exit;
        }

        $data = array(
            'name' => get('name'),
            'contact'  => get('contact'),
            'ort'   => get('ort'),
            'email'     => get('email'),
            'payed' => 0
        );

        $rules = array(
            'name' => array('required'),
            'contact' => array('required'),
            'ort' => array('required'),
            'email' => array('required', 'email'),
        );

        $messages = array(
            'name' => 'Bitte gib euren Teamnamen an',
            'contact' => 'Bitte gib einen Ansprechpartner an',
            'ort' => 'Bitte gib an, woher ihr seid',
            'email' => 'Bitte gib eine valide E-Mail Adresse an',
        );


        // some of the data is invalid
        if($invalid = invalid($data, $rules, $messages)) {
            $alert = $invalid;
        } else {

            // everything is ok, let's try to create a new registration
            try {

                addToStructure($page, 'teams', $data);

                $success = 'Vielen Dank für eure Anmeldung. Sobald die Stargebühr auf unserem Konto eingegangen ist erscheint Ihr unter "Bestätigte Mannschaften".';
                $data = array();

            } catch(Exception $e) {
                echo 'Your registration failed: ' . $e->getMessage();
            }
        }
    }

    return compact('alert', 'data', 'success');
};