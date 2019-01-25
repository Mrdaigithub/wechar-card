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

Route::get('/',
  function () {
    return view('welcome');
  });

Route::prefix('/wechat')->group(function () {
  Route::any('/', 'WeChatController@serve');
  Route::get("/authorize/user", "WeChatController@authorize_user");
  Route::get("/grant/user", "WeChatController@grant_user");
  Route::get('/token', 'WeChatController@getAccessToken');
  Route::get('/jssdk/config', 'WeChatController@getJsSdkConfig');
  Route::get('/oauth2/authorization/user',
    'WeChatController@oauth2_authorization_user');
});
