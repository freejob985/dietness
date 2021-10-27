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

use App\Http\Controllers\UserController;
$userprefix = 'user';
Route::group(['prefix' => $userprefix,'middleware' => 'auth:users'],function () use ($userprefix){
   Route::post('/verify_otp_is_first',[UserController::class , 'verify_otp_is_first'])->name($userprefix.'_verify_otp_is_first');
   Route::get('/getRemainingBoxes',[UserController::class , 'getRemainingBoxes'])->name($userprefix.'_getRemainingBoxes');
   Route::post('/payment',[UserController::class , 'payment'])->name($userprefix.'_payment');
   Route::post('/order',[UserController::class , 'order'])->name($userprefix.'_order');
   Route::get('/getOrderByDay',[UserController::class , 'getOrderByDay'])->name($userprefix.'_getOrderByDay');
   Route::get('/getUserReservedDates', [UserController::class, 'getUserReservedDates'])->name($userprefix . '_getUserReservedDates');
   Route::get('/getUserRestrictedDates', [UserController::class, 'getUserRestrictedDates'])->name($userprefix . 'getUserRestrictedDates');
   Route::get('/profile/get',[UserController::class , 'get_profile'])->name($userprefix.'_get_profile');
   Route::post('/profile/update',[UserController::class , 'UpdateProfile'])->name($userprefix.'_update_profile');
   Route::post('/profile/UpdateDelieveryTimeframe',[UserController::class , 'UpdateUserDelieveryTimeframe'])->name($userprefix.'_UpdateUserDelieveryTimeframe');
   Route::post('/profile/password/update',[UserController::class , 'UpdatePassword'])->name($userprefix.'_update_password_update');
   Route::post('/profile/address/add',[UserController::class , 'add_address'])->name($userprefix.'_add_address');
   Route::post('/profile/address/update',[UserController::class , 'change_address'])->name($userprefix.'_change_address');
   Route::post('/pauseDay',[UserController::class , 'CreateRestrictedDay'])->name($userprefix.'_pauseDay');
   Route::post('/removeUserOrder',[UserController::class , 'removeUserOrder'])->name($userprefix.'_removeUserOrder');
   Route::post('/unPauseDay',[UserController::class , 'unFreezeDay'])->name($userprefix.'_unFreezeDay');
   Route::post('/resendOtp',[UserController::class , 'resendOtp'])->name($userprefix.'_resendOtp');
   Route::post('/updateFcmToken',[UserController::class , 'updateFcmToken'])->name($userprefix.'_updateFcmToken');

});
Route::group(['prefix' => $userprefix],function () use ($userprefix){
    Route::post('/signup',[UserController::class , 'CreateNewUser'])->name($userprefix.'_CreateNewUser');
    Route::post('/login',[UserController::class , 'LoginUser'])->name($userprefix.'_LoginUser');
    Route::post('/forget',[UserController::class , 'forget_password'])->name($userprefix.'_Forget');
    Route::post('/verify_otp_forget',[UserController::class , 'verify_otp_forget'])->name($userprefix.'_verify_otp_forget');
    Route::post('/new_password_with_forget_otp',[UserController::class , 'new_password_with_forget_otp'])->name($userprefix.'_new_password_with_forget_otp');
    Route::get('/packages/get',[UserController::class , 'packages'])->name($userprefix.'_packages');
    Route::get('/packages/menu/get',[UserController::class , 'packages_withCustom'])->name($userprefix.'_packages_withCustom');
    Route::get('/categories/get',[UserController::class , 'categories'])->name($userprefix.'_categories');
    Route::get('/contact/mobile',[UserController::class , 'contact_mobile'])->name($userprefix.'_contact_mobile');
    Route::get('/getCities',[UserController::class , 'getCities'])->name($userprefix.'_getCities');
    Route::get('/getsliders',[UserController::class , 'getSliders'])->name($userprefix.'_getSliders');
    Route::get('/get_custom_remaining_boxes/{id}',[UserController::class , 'get_custom_remaining_boxes'])->name($userprefix.'_get_custom_remaining_boxes');
    Route::get('/settings',[UserController::class , 'get_main_settings'])->name($userprefix.'_get_main_settings');
});

