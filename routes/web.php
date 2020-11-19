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

$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');

$router->get('/user/profile', 'UserController@profile');

/* Checklist Templates */
$router->group([
    'prefix' => 'checklist/templates',
    'middleware' => 'auth'
], function($router){
    $router->get('/', 'TemplateController@index');
    $router->post('/', 'TemplateController@store');
    $router->get('{templateId}', 'TemplateController@show');
    $router->patch('{templateId}', 'TemplateController@update');
    $router->delete('{templateId}', 'TemplateController@destroy');
    $router->post('{templateId}/assign', 'TemplateController@assign');
});

/* Checklists */
$router->group([
    'prefix' => 'checklist',
    'middleware' => 'auth'
], function($router){
    $router->get('{checklistId}', 'ChecklistController@index');
    $router->post('/', 'ChecklistController@store');
    $router->get('{checklistId}', 'ChecklistController@show');
    $router->patch('{checklistId}', 'ChecklistController@update');
    $router->delete('{checklistId}', 'ChecklistController@destroy');
    $router->get('/', 'ChecklistController@index');
});

/* Items */
$router->group([
    'prefix' => 'checklist',
    'middleware' => 'auth'
], function($router){
    $router->post('complete', 'ItemController@complete'); // complete item(s)
    $router->post('incomplete', 'ItemController@incomplete'); // incomplete item(s)
    $router->get('{checklistId}/items', 'ItemController@index'); // get items by given checklistId
    $router->post('{checklistId}/items', 'ItemController@store'); // create item by given checklistId
    $router->get('{checklistId}/items/{itemId}', 'ItemController@show'); // get item bt checklistId and itemId
    $router->patch('{checklistId}/items/{itemId}', 'ItemController@update'); // update item by checklistId and itemId
    $router->delete('{checklistId}/items/{itemId}', 'ItemController@update'); // delete item by checklistId and itemId
    $router->post('{checklistId}/items/_bulk', 'ItemController@bulk'); // create bulk item by checklistId
    $router->get('{checklistId}/items/summaries', 'ItemController@update'); // get item summaries by checklistId
    $router->patch('items', 'ItemController@update'); // get all item
});

/* History */
$router->group([
    'prefix' => 'checklist',
    'middleware' => 'auth'
], function($router){
    $router->get('histories', 'HistoryController@index');
    $router->get('histories/{historyId}', 'HistoryController@store');
});
