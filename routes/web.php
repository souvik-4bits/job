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

$router->group(['prefix' => 'api'], function () use ($router) {
    //ADD points To Customer
    $router->post('/points', '\App\Http\Controllers\Api\pointsController@addPoints');

    //Void points of Customer

    $router->put('/void-points/{id}', '\App\Http\Controllers\Api\pointsController@voidPoints');

    //Fetch Point Transactions of customer

    $router->get('/customer-transaction/{id}', '\App\Http\Controllers\Api\customerController@getuserPoints');


    //Fetch Point Balance of customer
    $router->get('/customer-balance/{id}', '\App\Http\Controllers\Api\customerController@totalbalancePoint');


});


$router->get('/', function () {
    return response('',404);
});



