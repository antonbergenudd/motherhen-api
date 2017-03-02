<?php

use Illuminate\Http\Request;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function() {

	// Users
	Route::group(['prefix' => 'users'], function() {
		Route::get('index', 'UserController@index');
		Route::post('store', 'UserController@store');
		Route::put('updatePosition/{id}', 'UserController@updatePosition');
		Route::get('show/{id}', 'UserController@show');
		Route::get('show/{id}/requests', 'UserController@showRequests');
		Route::get('show/{id}/position', 'UserController@showPosition');
		Route::get('destroy/{id}', 'UserController@destroy');
	});

	// Groups
	Route::group(['prefix' => 'groups'], function() {
		Route::get('index', 'GroupController@index');
		Route::get('index/{id}', 'GroupController@getMembers');
		Route::get('index/{id}/positions', 'GroupController@getGroupPositions');
		Route::post('store', 'GroupController@store');
		Route::post('request/{id}', 'GroupController@makeRequest');


		Route::group(['prefix' => 'members'], function() {
			Route::post('store', 'GroupController@addMember');
		});
	});

	// Positions
	Route::group(['prefix' => 'positions'], function() {
		Route::get('index', 'PositionController@index');

		// Refactor
		Route::put('updatePosition/{id}', 'UserController@updatePosition');
		Route::get('index/{id}/positions', 'GroupController@getGroupPositions');
		Route::get('show/{id}/position', 'UserController@showPosition');
		Route::put('updatePosition/{id}', 'UserController@updatePosition');
	});

	// Requests
	Route::group(['prefix' => 'requests'], function() {
		Route::get('index', 'RequestController@index');
		Route::post('update/{id}', 'RequestController@update');

		// Refactor
		Route::post('request/{id}', 'GroupController@makeRequest');
		Route::get('show/{id}/requests', 'UserController@showRequests');
	});

});
