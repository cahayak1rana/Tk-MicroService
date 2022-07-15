# TK Microservice
This project is a quick-start mailing API for microservice mail sender. 

## Table of contents

- [Technologies](#technologies)
- [Installation](#installation)
- [Configuration](#configuration)
- [Custom mail driver](#custom-mail-driver)
    - [json Body request](#json-body-request)
- [Lumen](#lumen)

## Technologies
- Lumen, Lumen is chosen as part of the requirements of the task, since it iis lightweight and designed for microservice. 
- Custom mail transport is used by extending the mailable class of laravel. 
- Two mail custom drivers are used.
- Continue with Guzzle update and also . 
- Eloquent is used to store log information of emails.
- For queue and dispatcher, I choose to use the default queue of Laravel since it is easier to implement. 

## Installation
- Clone/fork the master branch.
- Add mysql-data folder in the root, e.g.
    ```
    Tk-microservice
    |-app
    |-resources
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
- Run the docker-compose command. 


## Custom mail driver.
The custom mail driver was previously intended to be built based on the custom transport of mailgun. But after trying to understand the requirements, I have decided to use simple PHP implementation of mail delivery that utilize the PHP mail functionality. Inspired by some code from O'Brien (https://eoghanobrien.com). The problem that I faced with this approach is the lack of feature and SMTP configuration, that is why I have decided to use SSMTP to have SMTP service on the server. 

The custom driver currently have two kinds of information:
- 



## 

#### Sending mail via JSON request.
You can access the sendmail via http://localhost:8000/api/sendmail

Here is the default json body that can be sent
```
{
	"subject": "My First Post!",
    "message": "This is my first mail",
    "from_email":   "gsk.player.12@gmail.com",
    "to": "gsk.player.12@gmail.com",
    "send_to": [
        {
            "send_to_email":"gsk.player.12@gmail.com",
            "send_to_name":"GSK"
        }
    ],
    "custom_id" : "AppGettingStartedTest",
    "mail_type" : "mailjet"
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
 - `mail_type` : Type of mail driver that you will use ('mailjet', 'mailgun', 'postman')
 


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


