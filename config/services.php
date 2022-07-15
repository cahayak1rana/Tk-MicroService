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
    'mailjet' => [
        'apikey'      => env('MAILJET_API'),
        'apisecret'   => env('MAILJET_API_SECRET')
    ],
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],
    'mandrill' => [
        'domain' => env('MANDRILL_DOMAIN'),
        'secret' => env('MANDRILL_SECRET'),
    ],
    'sendgrid' => [
        'domain' => env('SENDGRID_DOMAIN'),
        'secret' => env('SENDGRID_SECRET'),
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
    ]
];