<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\AuthenticationController;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('sendmail', ['uses' => 'SendMailController@sendMail']);
    $router->get('sendmail', ['uses' => 'SendMailController@sendMail']);
});

$router->get('login', ['as' => 'login', 'uses' => 'AuthenticationController@login']);
$router->post('post-login', ['as' => 'login.post', 'uses'=> 'AuthenticationController@postLogin']);
$router->get('registration', ['as' => 'register', 'uses' => 'AuthenticationController@registration']);
$router->post('post-registration', ['as' => 'register.post', 'uses' => 'AuthenticationController@postRegistration']); 
$router->get('reset', ['as' => 'reset', 'uses' => 'AuthenticationController@reset']);
$router->post('post-reset', ['as' => 'reset.post', 'uses' => 'AuthenticationController@postReset']); 
$router->get('dashboard', ['as' => 'dashboard', 'uses' => 'AuthenticationController@dashboard']); 
$router->get('logout', ['as' => 'logout', 'uses' => 'AuthenticationController@logout']);