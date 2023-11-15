<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function() {
    return redirect('/rooms');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/rooms', 'App\Http\Controllers\RoomsController@index');
    Route::get('/chat/{id}', 'App\Http\Controllers\RoomsController@chat');

    Route::get('/room/create', 'App\Http\Controllers\RoomsController@store');
    Route::post('/room/{id}/delete', 'App\Http\Controllers\RoomController@destroy');

    Route::post('/broadcast', 'App\Http\Controllers\PusherController@broadcast');
    Route::post('/receive', 'App\Http\Controllers\PusherController@receive');

    Route::post('/user/profile/picture/upload', 'App\Http\Controllers\UserController@updateProfilePicture');
    Route::post('/user/profile/username/update', 'App\Http\Controllers\UserController@updateUsername');

    Route::post('/online', 'App\Http\Controllers\PusherController@online');
});
