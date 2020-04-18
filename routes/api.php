<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('ping',function (Request $request) {
    throw  (new \App\Exceptions\ApiException(\App\Exceptions\ApiException::ERROR_TEST));
    return time();
});
//Route::get('resource/auth', 'Resources\Auth@get');
Route::middleware('auth:api')->get('resource/auth', 'Resources\Auth@get');
Route::middleware('auth:api')->post('tasks', 'Task@post');
Route::get('token', 'Token@get');
Route::get('resources/{id}', 'Resource@getOne');
Route::get('tasks/{id}', 'Task@getOne');
Route::get('callback/tasks', 'Callback\Task@getOne');
Route::any('callback/inputoss', 'Callback\Inputoss@post');

