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
   $router->post('/points','\App\Http\Controllers\Api\pointsController@addPoints');
   $router->post('/void-points','\App\Http\Controllers\Api\pointsController@voidPoints');
  $router->post('/getuser-point','\App\Http\Controllers\Api\customerController@getuserPoints');
    $router->get('/getuser-balance/{id}','\App\Http\Controllers\Api\customerController@totalbalancePoint');





});


