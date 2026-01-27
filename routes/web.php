<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->post('login', 'UserController@post');
$router->get('user', 'UserController@show');
/**$router->put('user/{id}', 'UserController@update');*/
$router->delete('user/{id}', 'UserController@delete');
/**$router->get('users', 'UserController@all');*/

$router->post('library/{id}', 'LibraryController@post');
$router->get('library/{id}', 'LibraryController@show');
$router->delete('library/{id}', 'LibraryController@delete');
$router->get('libraries/{id}', 'LibraryController@all');

$router->get('vault/{id}', 'VaultController@show');
$router->put('vault/{id}', 'VaultController@update');
