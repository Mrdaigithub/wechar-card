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

Route::namespace("Api\V1")
    ->prefix("v1")
    ->group(function () {
        Route::prefix("auth")->group(function () {
            Route::get("/", "AuthController@list");
            Route::get("/client/{openid}", "AuthController@getTokenByOpenid");
            Route::delete("/", "AuthController@invalidateToken");
        });
        Route::prefix("qrcode")->group(function () {
            Route::get("admin/login", "QrCodeController@adminLogin");
            Route::get("add/boss", "QrCodeController@addShopBoss")->middleware("token.refresh");
            Route::get("shop/{id}/add/employee", "QrCodeController@addShopEmployee")->middleware("token.refresh");
            Route::get("writeoff/{id}", "QrCodeController@writeOff")->middleware("token.refresh");
        });
        Route::prefix("user")->group(function () {
            Route::get("/", "UserController@list")->middleware("token.refresh", "identity.admin");
            Route::get("/plain_user", "UserController@listPlainUser")->middleware("token.refresh", "identity.admin");
            Route::get("/shop", "UserController@listShopEmployee")->middleware("token.refresh", "identity.admin");
            Route::get("/shop/{id}", "UserController@listShopEmployeeByShopId")->middleware("token.refresh", "identity.boss");
            Route::get("/{id}", "UserController@getUserById")->middleware("token.refresh");
            Route::post("/", "UserController@save")->middleware("token.refresh");
            Route::put("/plain_user/{id}", "UserController@updatePlainUser")->middleware("token.refresh");
            Route::put("/shop/{id}", "UserController@updateShopEmployee")->middleware("token.refresh", "identity.admin");
            Route::delete("/shop/{id}", "UserController@removeShopEmployee")->middleware("token.refresh", "identity.admin");
            Route::delete("/shop/employee/{id}", "UserController@removeShopEmployeeByBoss")->middleware("token.refresh", "identity.boss");
        });
        Route::prefix("system/config")->group(function () {
            Route::get("/", "SystemConfigController@list")->middleware("token.refresh");
            Route::put("/{id}", "SystemConfigController@updateSystemConfig")->middleware("token.refresh", "identity.admin");
        });
        Route::prefix("card")->group(function () {
            Route::get("/", "CardController@list")->middleware("token.refresh", "identity.admin");
            Route::get("/shop/{id}", "CardController@getCardByShopId")->middleware("token.refresh");
            Route::get("/user/shop/{id}", "CardController@getUserCardByShopId")->middleware("token.refresh");
            Route::get("/lottery/shop/{id}", "CardController@getLotteryCardIdByShopId")->middleware("token.refresh", "identity.user");
            Route::post("/", "CardController@storeCardModel")->middleware("token.refresh", "identity.admin");
            Route::put("/{id}", "CardController@updateCardModel")->middleware("token.refresh", "identity.admin");
            Route::delete("/{id}", "CardController@removeCardModel")->middleware("token.refresh", "identity.admin");
        });
        Route::prefix("shop")->group(function () {
            Route::get("/", "ShopController@list")->middleware("token.refresh", "identity.admin");
            Route::get("/boss", "ShopController@getShopByBoss")->middleware("token.refresh", "identity.boss");
            Route::get("/{id}", "ShopController@getShopById")->middleware("token.refresh");
            Route::post("/", "ShopController@store")->middleware("token.refresh", "identity.admin");
            Route::put("/{id}", "ShopController@update")->middleware("token.refresh", "identity.admin");
            Route::delete("/{id}", "ShopController@remove")->middleware("token.refresh", "identity.admin");
        });
        Route::prefix("activity")->group(function () {
            Route::get("/", "ActivityController@list")->middleware("token.refresh", "identity.admin");
            Route::get("/shop/{id}", "ActivityController@getActivityByShopId")->middleware("token.refresh");
            Route::post("/", "ActivityController@store")->middleware("token.refresh", "identity.admin");
            Route::put("/{id}", "ActivityController@update")->middleware("token.refresh", "identity.admin");
            Route::delete("/{id}", "ActivityController@remove")->middleware("token.refresh", "identity.admin");
        });
        Route::prefix("signin")->group(function () {
            Route::get("/user/{id}", "SignInController@getSignInLogByUserId")->middleware("token.refresh", "identity.user");
            Route::put("/user/{id}", "SignInController@UpdateTodaySignInLogByUserId")->middleware("token.refresh", "identity.user");
        });
        Route::prefix("log")->group(function () {
            Route::get("/winning", "LogController@listWinningWriteOffLog")->middleware("token.refresh", "identity.admin");
            Route::get("/winning/shop/{id}", "LogController@listWinningWriteOffLogByShopId")->middleware("token.refresh", "identity.boss");
        });
    });
