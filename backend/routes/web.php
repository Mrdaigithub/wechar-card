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
    return "ok";
});

Route::prefix('/wechat')->group(function () {
    Route::any('/', 'WeChatController@serve');
    Route::get("/authorize", "WeChatController@wechatBaseAuthorize");
    Route::get("/grant/lottery/user/openid", "WeChatController@grantLotteryUserOpenid");
    Route::get("/grant/lottery/user/info", "WeChatController@grantLotteryUserInfo");
    Route::get("/grant/shop", "WeChatController@grantToShop");
    Route::get("/grant/login/admin", "WeChatController@grantLoginAdmin");
    Route::get("/grant/add/boss/openid", "WeChatController@grantAddShopBossOpenid");
    Route::get("/grant/add/boss/info", "WeChatController@grantAddShopBossUserInfo");
    Route::get("/grant/add/employee", "WeChatController@grantAddShopEmployee");
    Route::get("/grant/writeoff", "WeChatController@grantWriteOff");
    Route::get('/geocoder', 'WeChatController@geocoder');
    Route::get('/shop/{id}/location', 'WeChatController@getCityByShopId');
});
