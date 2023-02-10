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

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->group(['prefix' => 'api'], function () use ($router) {
    // Matches "/api/register
    $router->post('registermember', 'AuthController@registerMember');
    $router->post('registerguest', 'AuthController@registerGuest');
      // Matches "/api/login
     $router->post('loginmember', 'AuthController@loginMember');
     $router->post('loginguest', 'AuthController@loginGuest');

    // Matches "/api/profile
    $router->get('profile', 'UserController@profile');

    // Matches "/api/users/1 
    //get one user by id
    $router->get('users/{id}', 'UserController@singleUser');

    // Matches "/api/users
    $router->get('users', 'UserController@allUsers');

    
});


/*  Install jwt : 
composer require tymon/jwt-auth"
    Generate jwt secret :
 php artisan jwt:secret

*/