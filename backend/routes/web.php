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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/wechat')->group(function () {
    Route::any('/', 'WeChatController@serve');
    Route::any('/test', 'WeChatController@test');
    Route::get("/authorize", "WeChatController@wechatAuthorize");
    Route::get("/grant/lottery/user", "WeChatController@grantLotteryUser");
    Route::get("/grant/login/admin", "WeChatController@grantLoginAdmin");
    Route::get("/grant/add/boss", "WeChatController@grantAddShopBoss");
    Route::get("/grant/add/employee", "WeChatController@grantAddShopEmployee");
    Route::get("/grant/writeoff", "WeChatController@grantWriteOff");
    Route::get('/token', 'WeChatController@getAccessToken');
    Route::get('/jssdk/config', 'WeChatController@getJsSdkConfig');
    Route::get('/geocoder', 'WeChatController@geocoder');
    Route::get('/shop/{id}/location', 'WeChatController@getCityByShopId');
});
