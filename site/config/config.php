<?php

return [
    'panel' =>[
        'install' => false
    ],
    'debug'  => true,
    'url' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'],
    'mailerHost' => 'sslout.df.eu',
    'mailerFrom' => 'kontaktformular@sf-bronnen.de',
    'mailerPassword' => 'syf!/j2juxKe',
    'MailerSSLPort' => '465',
    'backendApiUri' =>'http://localhost:1949/api'
];