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

$router->group(['prefix' => 'api/clients'], function () use ($router) {
    $router->post('/', 'ClientController@create');
    $router->get('/', 'ClientController@getAll');
    $router->get('/{id}', 'ProductController@show');
    $router->put('/{id}', 'ClientController@update');
    $router->delete('/{id}', 'ClientController@delete');
    $router->post('/email/', 'ClientController@getByEmail');
});

$router->group(['prefix' => 'api/products'], function () use ($router) {
    $router->get('/', 'ProductController@index');
    $router->post('/', 'ProductController@create');
    $router->get('/{id}', 'ProductController@show');
    $router->put('/{id}', 'ProductController@update');
    $router->delete('/{id}', 'ProductController@delete');
    $router->post('/search/', 'ProductController@search');
});

$router->group(['prefix' => 'api/product-photos'], function ($router) {
    $router->post('/', 'ProductPhotoController@create');
    $router->put('/{id}', 'ProductPhotoController@update');
    $router->delete('/{id}', 'ProductPhotoController@delete');
    $router->get('/{productId}', 'ProductPhotoController@getByProductId');
});
