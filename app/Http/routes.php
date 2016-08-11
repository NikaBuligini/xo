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
    Route::get('/board/{board}', 'HomeController@board');
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();
});

Route::group(['prefix' => 'api', 'middleware' => ['web']], function() {
	Route::group(['prefix' => 'me'], function() {
		Route::post('/', 'UserController@getAuth');
		Route::post('status/update', 'UserController@updateStatus');
		Route::get('match/request', 'UserController@sendMatchRequest');
	});

  Route::group(['prefix' => 'match'], function() {
		Route::post('get', 'HomeController@getRequest');
		Route::post('request', 'HomeController@sendMatchRequest');
	});
});
