<?php

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

Route::namespace("Api\V1")->prefix("v1")->group(function () {
    Route::prefix('/qrcode')->group(function () {
        Route::get('/admin/login', 'QrCodeController@adminLogin');
        Route::get('/add/boss', 'QrCodeController@addShopBoss');
    });
    Route::prefix("auth")->group(function () {
        Route::get("/", "AuthController@list");
        Route::get("/client/{openid}", "AuthController@getTokenByOpenid");
        Route::delete("/", "AuthController@invalidateToken");
    });
    Route::prefix("user")->group(function () {
        Route::get("/", "UserController@list");
        Route::get("/plain_user", "UserController@listPlainUser");
        Route::get("/shop", "UserController@listShopEmployee");
        Route::get("/{id}", "UserController@getUserById");
        Route::post("/", "UserController@save");
        Route::put("/plain_user/{id}", "UserController@updatePlainUser");
        Route::put("/shop/{id}", "UserController@updateShopEmployee");
        Route::delete("/shop/{id}", "UserController@removeShopEmployee");
    });
    Route::prefix("system/config")->group(function () {
        Route::get("/", "SystemConfigController@list");
        Route::put("/{id}", "SystemConfigController@updateSystemConfig");
    });
    Route::prefix("card")->group(function () {
        Route::get("/", "CardController@list");
        Route::get("/shop/{id}", "CardController@getCardByShopId");
        Route::get("/user/shop/{id}", "CardController@getUserCardByShopId");
        Route::get("/lottery/shop/{id}", "CardController@getLotteryCardIdByShopId");
        Route::post("/", "CardController@storeCardModel");
        Route::put("/{id}", "CardController@updateCardModel");
        Route::delete("/{id}", "CardController@removeCardModel");
    });
    Route::prefix("shop")->group(function () {
        Route::get("/", "ShopController@list");
        Route::post("/", "ShopController@store");
        Route::put("/{id}", "ShopController@update");
        Route::delete("/{id}", "ShopController@remove");
    });
    Route::prefix("activity")->group(function () {
        Route::get("/", "ActivityController@list");
        Route::get("/shop/{id}", "ActivityController@getActivityByShopId");
        Route::post("/", "ActivityController@store");
        Route::put("/{id}", "ActivityController@update");
        Route::delete("/{id}", "ActivityController@remove");
    });
    Route::prefix("signin")->group(function () {
        Route::get("/user/{id}", "SignInController@getSignInLogByUserId");
        Route::put("/user/{id}", "SignInController@UpdateTodaySignInLogByUserId");
    });
});
