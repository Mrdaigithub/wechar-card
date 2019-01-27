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

Route::namespace("Api\V1")->prefix("v1")->group(function() {
  Route::prefix("user")->group(function() {
    Route::get("/", "UserController@list");
    Route::post("/", "UserController@save");
    Route::get("/{id}", "UserController@get_user_by_id");
    Route::get("/openid/{openid}", "UserController@get_user_by_openid");
    Route::put("/{id}", "UserController@update");
    Route::delete("/{id}", "UserController@remove");
  });
  Route::prefix("system/config")->group(function() {
    Route::get("/", "SystemConfigController@list");
  });
  Route::prefix("card")->group(function() {
    Route::get("/", "CardController@list");
    Route::get("/shop/{id}", "CardController@getCardByShopId");
    Route::get("/lottery/shop/{id}", "CardController@getLotteryCardIdByShopId");
  });
  Route::prefix("activity")->group(function() {
    Route::get("/", "ActivityController@list");
    Route::get("/shop/{id}", "ActivityController@getActivityByShopId");
  });
});
