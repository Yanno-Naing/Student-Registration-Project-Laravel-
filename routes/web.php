<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('lang', function (){
    echo __('messages.welcome');
})->middleware('setlocale');

Route::get('/home', function () {
    echo __('messages.welcome');
});

Route::middleware('setlocale')->group(function(){
    Route::get('/lang', function (){
        echo __('messages.welcome');
    });

    Route::get('/home', function () {
        echo __('messages.welcome');
    });
});