<?php
use Illuminate\Redis;

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


$router->group(['prefix' => 'employee'], function() use($router) {
    $router->get('/list', 'EmployeeController@index');
    $router->get('/detail/{id}', 'EmployeeController@show');
    $router->post('/detail', 'EmployeeController@getEmpId');
    $router->post('/insert', 'EmployeeController@store');
    $router->put('/edit/{id}', 'EmployeeController@update');
    // $router->delete('/delete/{id}', 'EmployeeController@softDelete');
    $router->put('/restore/{id}', 'EmployeeController@restore');
    $router->put('/delete/{id}', 'EmployeeController@delete');
});