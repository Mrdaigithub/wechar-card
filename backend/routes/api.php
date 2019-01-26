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

Route::namespace("Api\V1")
     ->prefix("v1")
     ->group(function() {
       Route::prefix("user")->group(function() {
         Route::get("/", "UserController@list");
         Route::post("/", "UserController@save");
         Route::get("/{id}", "UserController@get_user_by_id");
         Route::get("/openid/{openid}", "UserController@get_user_by_openid");
         Route::put("/{id}", "UserController@update");
         Route::delete("/{id}", "UserController@remove");
       });
     });
