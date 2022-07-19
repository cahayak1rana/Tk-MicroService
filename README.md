# TK Microservice
This project is a quick-start mailing API for microservice mail sender. 

## Table of contents

- [Technologies](#technologies)
- [Installation](#installation)
- [Configuration](#configuration)
- [Custom mail driver](#custom-mail-driver)
    - [json Body request](#json-body-request)
- [Command line access](#command-line-access)
- [Queue] (#queue)
- [Logging] (#logging)
- [Lumen](#lumen)
- [Testing](#testing)
- [Extras](#extras)
- [Todos](#todos)

## Technologies
- Lumen, Lumen is chosen as part of the requirements of the task, since it iis lightweight and designed for microservice. 
- Custom mail transport is used by extending the mailable class of laravel. 
- Two mail custom drivers are used, you can try to send a request to http://localhost:8000/api/sendmail/, see #json-body-request for list of parameters.
- Continue with Guzzle update and also . 
- Eloquent is used to store log information of emails.
- For queue and dispatcher, I choose to use the default queue of Laravel since it is easier to implement. 
- Simple registration and reset password features were built on the system that can be accessed on port 8001 (http://localhost:8001/registration).  

## Installation
- Clone/fork the master branch.
- Add mysql-data folder in the database folder, e.g.
    ```
    Tk-microservice
    |-app
    |-resources
    |-database
        |-mysql-data
    ```
- Setup 
- Configure the SMTP framework in the ssmtp.conf. e.g. (for mailtrap)
    ```
    root=postmaster
    mailhub=smtp.mailtrap.io:2525
    FromLineOverride=YES
    AuthUser=
    AuthPass=
    UseSTARTTLS=YES
    ```
- Run the docker-compose command in the root folder of this project. 
    ```
    docker-compose --env-file .env up --build
    ```
- Login to the docker lumen shell environment, and execute the migration instruction to generate the tables.
    ```
    php artisan migrate
    ```

## Custom mail driver.
The custom mail driver was previously intended to be built based on the custom transport of mailgun. But after trying to understand the requirements, I have decided to use simple PHP implementation of mail delivery that utilize the PHP mail functionality. Inspired by some code from O'Brien (https://eoghanobrien.com). The problem that I faced with this approach is the lack of feature and SMTP configuration, that is why I have decided to use SSMTP to have SMTP service on the server. 
HTML is supported on all available drivers by supplying the `mail_type = 'html'`. 

The custom driver has three kinds of place: 
- `\config\services.php` This file contains the information of third party services that are being used by the custom mail driver. You need to configure this and the 
    ```
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
            'sendinblue' => [
                'domain' => env('SENDINBLUE_DOMAIN'),
                'secret' => env('SENDINBLUE_SECRET'),
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
    ```

- `.env` Please update your .env according to the specified environment variable on the services file.
    ```
    MAILJET_API=""
    MAILJET_API_SECRET="KEY"

    MANDRILL_DOMAIN="smtp.mandrillapp.com"
    MANDRILL_SECRET="KEY"

    SENDGRID_SECRET="KEY"
    SENDGRID_DOMAIN="https://api.sendgrid.com/v3/mail/send"
    ```

- `\App\CustomMailer\CustomTransport.php:send()` This function need to be updated accordingly if you want to add extra mail driver. Currently we only support sendgrid, mailjet and the fallback to SMTP mailer by ssmtp. 
    Currently I have implemented the email sending using GuzzleHTTP request (for sending API requests to different mail drivers, e.g. sendgrid, mailjet) and also default mail() function of PHP for fallback. 

## 

#### json Body request
You can access the sendmail functionality with POST request via http://localhost:8000/api/sendmail

Here is the default json body that can be sent
```
{
	"subject":          "My First Post!",
    "message":          "This is my first mail",
    "from_email":       "asdf@gmail.com",
    "to":               "asdf@gmail.com",
    "send_to":          [
                            {
                                "send_to_email":"gsk.player.12@gmail.com",
                                "send_to_name":"GSK"
                            }
                        ],
    "custom_id":        "AppGettingStartedTest",
    "mail_type":        "mailjet"
}
```

There are several `Parameters` that you can set on the json body:

`$params` will be a PHP associative array with the following keys :

 - `subject`: The subject of the message.
 - `message` : ID you want to apply a POST request to (used in case of action on a resource) 
 - `from_email` : The email address of the sender
 - `from_name`  : The name of the sender
 - `send_to` : associative PHP array of sendto addresses. The properties correspond to the property of the JSON Payload)
 - `custom_id` : ID you want to apply a POST request to (used in case of action on a resource) 
 - `driver_type` : Type of mail driver that you will use ('mailjet', 'mailgun', 'postman')
 - `mail_type`: html and/or plaintext
 
## Command line access
Command line access is done using the default artisan command. Here are the list of available options
```
    *{from-email : Email address of the sender} 
    *{from-name : The name of the sender} 
    *{subject}
    *{message} 
    *{mail-type} 
    *{driver-type}
    *{custom-id}
    *{queue}
    *{send-to* : Array of recipients, use string format with pipe as separator e.g array("test@test.com|Testing user")} 
```
All options are required at the moment. 

## Queue
Since Lumen has access to Laravel Queue, I utilize this to dispatch the process. Asynchronous is part of the task, so I need to implement it in database mode instead of the default sync mode.  On all request, user only need to specify queue parameter with true and this process will be queued to the job table.

## Logging
Simple logging is available in the form of database table called emails. 

##  Testing 
I choose to use the default unit testing provided by Laravel. There are two unit testing that I did:
- Testing email sending using postman API. 
- Testing email command using php artisan. 

##  Extras
A simple registration and reset password page were built as part of the microservice. This can be accessed on `http://localhost:8001`. There are two routes:
- `http://localhost:8001/registration/`
- `http://localhost:8001/reset/`
The page won't have redirection and authentication due to limitation of Laravel lumen.

## Todos
This code is far from perfect, there are several things that can be improved:
- Clean and beautify the code.
- Adjusted the CustomTransport PHP class to be able to handle driver configuration via database. 
- Graceful error handling (failover to other services). 

# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel/lumen-framework)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/lumen-framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/lumen)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Contributing

Thank you for considering contributing to Lumen! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


