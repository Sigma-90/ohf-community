<?php

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

Route::group(['middleware' => ['auth', 'language']], function () {
    Route::prefix('shop')->name('shop.')->middleware(['can:validate-shop-coupons'])->group(function(){
        Route::get('/', 'ShopController@index')->name('index');
        Route::post('/', 'ShopController@redeem')->name('redeem');
        Route::post('/cancelCard', 'ShopController@cancelCard')->name('cancelCard');
        Route::get('/settings', 'ShopSettingsController@edit')->name('settings.edit')->middleware(['can:configure-shop']);
        Route::put('/settings', 'ShopSettingsController@update')->name('settings.update')->middleware(['can:configure-shop']);
    });
});
