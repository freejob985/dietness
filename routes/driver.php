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

use App\Http\Controllers\driver\driver;
$driverprefix = 'driver';
Route::group(['prefix' => $driverprefix],function () use ($driverprefix){
    Route::post('/login',[driver::class , 'Login'])->name($driverprefix.'_Login');
});
Route::group(['prefix' => $driverprefix ,'middleware' => 'auth:driver'],function () use ($driverprefix){
    Route::get('/orders',[driver::class , 'orders'])->name($driverprefix.'_orders');
    Route::post('/orders/update/status',[driver::class , 'orders_update_status'])->name($driverprefix.'_orders_update_status');
});

