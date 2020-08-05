<?php

use App\Book;
use App\Http\Resources\BookCollection;
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

Route::post('/register', 'Auth\RegisterController@create');
Route::post('/login','Auth\LoginController@login');

Route::group(['middleware' => ['auth:api']], function() {
    Route::get('/me','Auth\LoginController@me');
    Route::get('/logout','Auth\LoginController@logout');
});

Route::apiResource('book','API\BookController');
