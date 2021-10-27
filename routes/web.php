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
    return view('index');
})->name('home');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/store', function () {
    return view('store');
})->name('store');
Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');
Route::get('/lang/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'ar'])) {
        return abort(400);
    }
    Session::put('home_locale', $locale);
    return back();
})->name('lang');
Route::get('/pass', function () {
    return \Hash::make('123456');
})->name('pass');
Route::get('/mail','UserController@mail')->name('mail');
Route::get('/test','UserController@test')->name('test');
Route::get('/passwords',function(){
    return \Hash::make('12341234');
})->name('passwords');