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
            Route::get("add/boss", "QrCodeController@addShopBoss");
            Route::get("shop/{id}/add/employee", "QrCodeController@addShopEmployee");
            Route::get("writeoff/{id}", "QrCodeController@writeOff");
        });
        Route::prefix("user")->group(function () {
            Route::get("/", "UserController@list")->middleware("identity.admin");
            Route::get("/plain_user", "UserController@listPlainUser")->middleware("identity.admin");
            Route::get("/shop", "UserController@listShopEmployee")->middleware("identity.admin");
            Route::get("/shop/{id}", "UserController@listShopEmployeeByShopId")->middleware("identity.boss");
            Route::get("/{id}", "UserController@getUserById");
            Route::post("/", "UserController@save");
            Route::put("/plain_user/{id}", "UserController@updatePlainUser");
            Route::put("/shop/{id}", "UserController@updateShopEmployee")->middleware("identity.admin");
            Route::delete("/shop/{id}", "UserController@removeShopEmployee")->middleware("identity.admin");
            Route::delete("/shop/employee/{id}", "UserController@removeShopEmployeeByBoss")->middleware("identity.boss");
        });
        Route::prefix("system/config")->group(function () {
            Route::get("/", "SystemConfigController@list");
            Route::put("/{id}", "SystemConfigController@updateSystemConfig")->middleware("identity.admin");
        });
        Route::prefix("card")->group(function () {
            Route::get("/", "CardController@list")->middleware("identity.admin");
            Route::get("/shop/{id}", "CardController@getCardByShopId");
            Route::get("/user/shop/{id}", "CardController@getUserCardByShopId");
            Route::get("/lottery/shop/{id}", "CardController@getLotteryCardIdByShopId")->middleware("identity.user");
            Route::post("/", "CardController@storeCardModel")->middleware("identity.admin");
            Route::put("/{id}", "CardController@updateCardModel")->middleware("identity.admin");
            Route::delete("/{id}", "CardController@removeCardModel")->middleware("identity.admin");
        });
        Route::prefix("shop")->group(function () {
            Route::get("/", "ShopController@list")->middleware("identity.admin");
            Route::get("/boss", "ShopController@getShopByBoss")->middleware("identity.boss");
            Route::get("/{id}", "ShopController@getShopById");
            Route::post("/", "ShopController@store")->middleware("identity.admin");
            Route::put("/{id}", "ShopController@update")->middleware("identity.admin");
            Route::delete("/{id}", "ShopController@remove")->middleware("identity.admin");
        });
        Route::prefix("activity")->group(function () {
            Route::get("/", "ActivityController@list")->middleware("identity.admin");
            Route::get("/shop/{id}", "ActivityController@getActivityByShopId");
            Route::post("/", "ActivityController@store")->middleware("identity.admin");
            Route::put("/{id}", "ActivityController@update")->middleware("identity.admin");
            Route::delete("/{id}", "ActivityController@remove")->middleware("identity.admin");
        });
        Route::prefix("signin")->group(function () {
            Route::get("/user/{id}", "SignInController@getSignInLogByUserId")->middleware("identity.user");
            Route::put("/user/{id}", "SignInController@UpdateTodaySignInLogByUserId")->middleware("identity.user");
        });
        Route::prefix("log")->group(function () {
            Route::get("/winning", "LogController@listWinningWriteOffLog")->middleware("identity.admin");
            Route::get("/winning/shop/{id}", "LogController@listWinningWriteOffLogByShopId")->middleware("identity.boss");
        });
    });
