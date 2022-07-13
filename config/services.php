<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],
    'custom_mail' => [
        'url' => env('CUSTOM_MAIL_URL'),
        'key' => env('CUSTOM_MAIL_API_KEY')
    ],
    'mailtrap' => [
        'url'       => env('MAILTRAP_URL'),
        'port'      => env('MAILTRAP_PORT'),
        'username'  => env('MAILTRAP_USERNAME'),
        'password'  => env('MAILTRAP_PASSWORD'),
        'timeout'   => env('MAILTRAP_TIMEOUT')
    ],
    'mailjet' => [
        'auth'      => ['a3cc5a09618fbdc9f0421c118906b9d1', 'deb61b2a93d367ba994a71e9dc160462']
    ]
];