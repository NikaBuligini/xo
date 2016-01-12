<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/', 'HomeController@index');
    Route::get('/createBoard', 'HomeController@createBoard');
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();
});

Route::group(['prefix' => 'api', 'middleware' => ['web']], function() {
	Route::group(['prefix' => 'me'], function() {
		Route::post('/', 'UserController@getAuth');
		Route::post('status/update', 'UserController@updateStatus');
	});
});
