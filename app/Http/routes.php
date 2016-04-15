<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    $arr = array();
    $arr['name'] = "";

    return view('dashboard', $arr);
});


//Route::resource('projects', 'ProjectController');
//Route::resource('tasks', 'TaskController');
//Route::resource('users', 'UserController');
//Route::resource('time', 'TimeController');

Route::group(['prefix' => 'api/v1', 'middleware' => 'apiable'], function() {
    Route::resource('projects', 'ProjectController');
    Route::resource('tasks', 'TaskController');
    Route::resource('users', 'UserController');
    Route::resource('time', 'TimeController');
});