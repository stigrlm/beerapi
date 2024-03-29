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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('beer/{id}', 'App\Http\Controllers\BeerController@show');
Route::post('beer', 'App\Http\Controllers\BeerController@store');
Route::put('beer/{id}', 'App\Http\Controllers\BeerController@update');
Route::delete('beer/{id}', 'App\Http\Controllers\BeerController@destroy');
Route::get('beers', 'App\Http\Controllers\BeerController@list');
