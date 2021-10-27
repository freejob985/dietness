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
use App\Http\Controllers\admin\admin;
use App\Http\Controllers\driver\driver;
$prefix = 'admin';
$prefix_driver = 'driver';
Route::get('admin/lang/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'ar'])) {
        return abort(400);
    }
    Session::put('admin_locale', $locale);
    return back();
})->name('admin.lang');
Route::group(['prefix' => $prefix],function() use($prefix){
        Route::get('login',[admin::class ,'login'])->name($prefix . '_login');
        Route::post('login',[admin::class ,'Dologin'])->name($prefix . '_login_post');
});
Route::group(['prefix' => 'admin','middleware' => ['AdminAuth', 'has.permission']],function() use($prefix){
    Route::get('/logout',[admin::class,'logout'])->name($prefix.'_logout');
    Route::get('/',[admin::class,'index'])->name($prefix.'_index');
    Route::get('/broadCast',[admin::class,'getbroadCastToAll'])->name($prefix.'_broadCastToAll');
    Route::get('/export',[admin::class,'exportOrders'])->name($prefix.'_exportOrders');
    Route::get('/exportOrdersinDay',[admin::class,'exportOrdersinDay'])->name($prefix.'_exportOrdersinDay');
    Route::get('/delete',[admin::class,'deleteOrders'])->name($prefix.'_deleteOrders');
    Route::get('/complete',[admin::class,'completeOrders'])->name($prefix.'_completeOrders');
    Route::get('/export/subscriptions',[admin::class,'subscriptionsExport'])->name($prefix.'_subscriptionExport');
    Route::post('/broadCast',[admin::class,'postbroadCastToAll'])->name($prefix.'_add_post_broadCast');
    
    Route::group(['prefix' => 'categories'],function() use($prefix){
        Route::get('/',[admin::class,'categories'])->name($prefix.'_categories');
        Route::get('/add',[admin::class,'add_get_categories'])->name($prefix.'_add_get_categories');
        Route::get('/edit/{id}',[admin::class,'main_categories_get_edit'])->name($prefix.'_main_categories_edit');
        Route::post('/edit/{id}',[admin::class,'main_categories_post_edit'])->name($prefix.'_main_categories_post_edit');
        Route::get('/remove/{id}',[admin::class,'main_categories_get_remove'])->name($prefix.'_main_categories_remove');
        Route::post('/add',[admin::class,'add_post_categories'])->name($prefix.'_add_post_categories');
    });
    Route::group(['prefix' => 'products'],function() use($prefix){
        Route::get('/',[admin::class,'products'])->name($prefix.'_products');
        Route::get('/add',[admin::class,'add_get_products'])->name($prefix.'_add_get_products');
        Route::post('/add',[admin::class,'add_post_products'])->name($prefix.'_add_post_products');
        Route::get('/edit/{id}',[admin::class,'get_products_edit'])->name($prefix.'_products_edit');
        Route::post('/edit/{id}',[admin::class,'post_products_edit'])->name($prefix.'_post_products_edit');
        Route::get('/remove/{id}',[admin::class,'get_products_remove'])->name($prefix.'_get_products_remove');
        Route::post('/change/order',[admin::class,'change_products_order'])->name($prefix.'_change_products_order');
    });
    Route::group(['prefix' => 'packages'],function() use($prefix){
        Route::get('/',[admin::class,'packages'])->name($prefix.'_packages');
        Route::get('/{id}/add/plans',[admin::class,'add_plans_in_single_package'])->name($prefix.'_packages_add_plan');
        Route::get('/{id}/plans',[admin::class,'get_package_plans'])->name($prefix.'_get_package_plans');
        Route::get('/edit/{id}',[admin::class,'edit_packages'])->name($prefix.'_packages_edit');
        Route::post('/edit/{id}',[admin::class,'edit_post_packages'])->name($prefix.'_edit_post_packages');
        Route::get('/add',[admin::class,'add_get_packages'])->name($prefix.'_add_get_packages');
        Route::post('/add',[admin::class,'add_post_packages'])->name($prefix.'_add_post_packages');
        Route::get('/remove/{id}',[admin::class,'get_packages_remove'])->name($prefix.'_packages_remove');
    }); 
    Route::group(['prefix' => 'plans'],function() use($prefix){
        Route::post('/edit/{id}',[admin::class,'edit_plan_by_id'])->name($prefix.'_edit_plan_by_id');
        Route::post('/add/{package}',[admin::class,'create_new_plan'])->name($prefix.'_create_new_plan');
        Route::post('/remove/',[admin::class,'remove_plan'])->name($prefix.'_remove_plan');
    });    
    Route::group(['prefix' => 'users'],function() use($prefix){
        Route::get('/',[admin::class,'users'])->name($prefix.'_users');
        Route::get('users_search',[admin::class,'users_search'])->name($prefix.'_users_search');
        Route::get('/{user}',[admin::class,'UserChangeStatus'])->name($prefix.'_UserChangeStatus');
        Route::get('/view/{user}',[admin::class,'Userview'])->name($prefix.'_Userview');
        
        Route::post('/edit/{user}',[admin::class,'UserEdit'])->name($prefix.'_post_edit_users');
        Route::get('/remove/{user}',[admin::class,'UserRemove'])->name($prefix.'_remove_users');
        Route::post('/status',[admin::class,'accept_user'])->name($prefix.'_update_user');
    });
    Route::get('user/addnew',[admin::class,'add_new_user_get'])->name($prefix.'_new_users');
    Route::post('user/addnew',[admin::class,'add_new_user_post'])->name($prefix.'_post_new_users');
    Route::get('/boxes',[admin::class,'boxes'])->name($prefix.'_boxes');
    Route::group(['prefix' => 'subscriptions'],function() use($prefix){
        Route::get('/',[admin::class,'subscriptions'])->name($prefix.'_subscriptions');
        Route::post('/update',[admin::class,'update_subscription'])->name($prefix.'_update_subscription');
        Route::get('/edit/{id}',[admin::class ,'get_subscriptions_edit'])->name($prefix . '_subscriptions_edit');
        Route::post('/edit/{id}',[admin::class ,'post_subscriptions_edit'])->name($prefix . '_subscriptions_edit_post');
        Route::get('/addnew',[admin::class ,'add_get_subscription'])->name($prefix . '_add_get_subscription');
        Route::post('/addnew',[admin::class ,'add_post_subscription'])->name($prefix . '_add_post_subscription');
    });
    Route::group(['prefix' => 'sliders'],function() use($prefix){
        Route::get('/add',[admin::class,'addSlider'])->name($prefix.'_addSlider');
        Route::post('/add',[admin::class,'addpostSlider'])->name($prefix.'_add_post_slider');
        Route::get('/edit/{id}',[admin::class,'editSlider'])->name($prefix.'_editSlider');
        Route::post('/edit/{id}',[admin::class,'editpostSlider'])->name($prefix.'_edit_post_slider');
        Route::get('/remove/{id}',[admin::class,'slider_remove'])->name($prefix.'_slider_remove');
        Route::get('/',[admin::class,'sliders'])->name($prefix.'_sliders');
    });
    
    Route::get('/governorates',[admin::class ,'Governorates'])->name($prefix . '_governorates');
    Route::get('/governorates/add',[admin::class ,'add_get_governorates'])->name($prefix . '_governorates_add_get');
    Route::get('/governorates/edit/{id}',[admin::class ,'edit_get_governorates'])->name($prefix . '_governorates_edit_get');
    Route::post('/governorates/add',[admin::class ,'add_post_governorates'])->name($prefix . '_governorates_add_post');
    Route::post('/governorates/edit/{id}',[admin::class ,'edit_post_governorates'])->name($prefix . '_governorates_edit_post');
    Route::get('/governorates/remove/{id}',[admin::class ,'governorates_delete'])->name($prefix . '_governorates_delete');
    
    
    Route::get('/cities',[admin::class ,'cities'])->name($prefix . '_cities');
    Route::get('/cities/add',[admin::class ,'add_get_cities'])->name($prefix . '_cities_add_get');
    Route::get('/cities/edit/{id}',[admin::class ,'edit_get_cities'])->name($prefix . '_cities_edit_get');
    Route::post('/cities/add',[admin::class ,'add_post_cities'])->name($prefix . '_cities_add_post');
    Route::post('/cities/edit/{id}',[admin::class ,'edit_post_cities'])->name($prefix . '_cities_edit_post');
    Route::get('/cities/remove/{id}',[admin::class ,'cities_delete'])->name($prefix . '_cities_delete');
    
    
    Route::get('/orders',[admin::class ,'get_orders'])->name($prefix . '_orders');
    Route::get('/orders/view/{id}',[admin::class ,'viewOrder'])->name($prefix . '_viewOrder');
    Route::get('/orders/download/{id}',[admin::class ,'DownloadOrder'])->name($prefix . '_DownloadOrder');
    Route::get('/orders/download/{id}/word',[admin::class ,'DownloadOrder_word'])->name($prefix . '_DownloadOrder_word');
   
    Route::get('/drivers',[admin::class ,'drivers'])->name($prefix . '_drivers');
    Route::get('/drivers/add',[admin::class ,'addDrivers'])->name($prefix . '_add_drivers');
    Route::get('/drivers/edit/{id}',[admin::class ,'editDrivers'])->name($prefix . '_edit_drivers');
    Route::post('/drivers/edit/{id}',[admin::class ,'editDriversPost'])->name($prefix . '_editDriversPost');
    Route::get('/drivers/remove/{id}',[admin::class ,'removeDrivers'])->name($prefix . '_drivers_delete');
    Route::post('/drivers/add',[admin::class ,'addDriversPost'])->name($prefix . '_addDriversPost');

    Route::get('/settings',[admin::class ,'getSettings'])->name($prefix . '_settings');
    Route::post('/settings',[admin::class ,'postSettings'])->name($prefix . '_post_settings');
    Route::get('/terms',[admin::class ,'getTerms'])->name($prefix . '_getTerms');
    Route::post('/terms',[admin::class ,'postTerms'])->name($prefix . '_postTerms');
    
    Route::get('/Welcome_text',[admin::class ,'getWelcomeText'])->name($prefix . '_getWelcomeText');
    Route::post('/Welcome_text',[admin::class ,'postWelcomeText'])->name($prefix . '_postWelcomeText');
    
    Route::resource('admins', AdminController::class)->except(['show']);
    
    
});
Route::group(['prefix' => $prefix_driver , 'middleware' => ['AuthDriver']],function() use($prefix_driver){
        Route::get('',[driver::class ,'index'])->name($prefix_driver . '_index');
        Route::get('/orders',[driver::class ,'get_orders'])->name($prefix_driver . '_orders');
        Route::get('/orders/view/{id}',[driver::class ,'viewOrder'])->name($prefix_driver . '_viewOrder');
});
Route::group(['prefix' => $prefix_driver],function() use($prefix_driver){
        Route::get('login',[driver::class ,'login_web'])->name($prefix_driver . '_login');
        Route::post('login',[driver::class ,'Dologin_web'])->name($prefix_driver . '_login_post');
});

