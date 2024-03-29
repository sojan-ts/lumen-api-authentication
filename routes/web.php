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
$router->group(['prefix' => 'api/auth'], function () use ($router) {

    $router->post('registermember', 'AuthController@registerMember');
    $router->post('registerguest', 'AuthController@registerGuest');

    $router->post('loginmember', 'AuthController@loginMember');
    $router->post('loginguest', 'AuthController@loginGuest');

    $router->post('refreshmember','AuthController@refreshMember');
    $router->post('refreshguest','AuthController@refreshGuest');

});

$router->group(['prefix' => 'api/user'], function () use ($router) {
    $router->post('insertcategory', 'UserController@InsertCategory');
    $router->patch('updatecategory/{id}', 'UserController@UpdateCategory');
    $router->get('paginatecategory', 'UserController@PaginateCategory');
    $router->post('uploadimagecategory/{id}', 'UserController@UploadImageCategory');
});

$router->group(['prefix' => 'api/g'], function () use ($router) {
    $router->get('users', 'GuestController@allUsers'); 
});


/*  Install jwt : 
composer require tymon/jwt-auth"
    Generate jwt secret :
 php artisan jwt:secret

*/