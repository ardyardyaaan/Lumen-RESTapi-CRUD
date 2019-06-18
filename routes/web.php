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

$router->get('/employees', 'EmployeeController@listEmp');
$router->get('/employee/{id}', 'EmployeeController@emp');
$router->post('/employee', 'EmployeeController@store');
$router->put('/employee/{id}', 'EmployeeController@update');
$router->delete('/employee/{id}', 'EmployeeController@softDelete');
$router->put('/employee/restore/{id}', 'EmployeeController@restore');
$router->delete('/employee/destroy/{id}', 'EmployeeController@destroy');