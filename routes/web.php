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

Route::get('/','PagesController@root')->name('root');

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index'); //收货地址列表
    Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create');//新增和编辑收货地址列表
    Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store');       //添加收获地址
});