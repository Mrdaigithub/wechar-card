<?php
/**
 * Created by PhpStorm.
 * User: mrdai
 * Date: 2018-12-19
 * Time: 21:06
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Handler\EventMessageHandler;
use App\Http\Controllers\Handler\TextMessageHandler;
use App\Model\Activity;
use App\Model\Shop;
use EasyWeChat\Kernel\Messages\Message;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WeChatController extends Controller {
  
  public function test() {
  }
  
  public function authorize_user(Request $request) {
    if (!$request->exists("url")) {
      return "参数错误";
    }
    $url = $request->get("url");
    
    $app      = app('wechat.official_account');
    $response = $app->oauth->scopes(['snsapi_base'])->redirect($url);
    return $response;
  }
  
  public function grant_user() {
    $app  = app('wechat.official_account');
    $user = $app->oauth->user();
    return view("redirectUser",
      [
        "openid" => $user->getId(),
        "url"    => env("FRONT_DOMAIN") . "/user/lottery",
      ]);
  }
  
  /**
   * 处理微信的请求消息
   *
   * @return string
   */
  public function serve() {
    $app = app('wechat.official_account');
    
    $app->server->push(TextMessageHandler::class, Message::TEXT); // 文本消息
    $app->server->push(EventMessageHandler::class, Message::EVENT); // 事件消息
    
    return $app->server->serve();
  }
  
  /**
   * 获取Access token
   *
   * @return mixed
   */
  public function getAccessToken() {
    $app = app('wechat.official_account');
    
    return $app->access_token->getToken();
  }
  
  /**
   * 获取js sdk 配置
   *
   * @return mixed
   */
  public function getJsSdkConfig() {
    $app = app('wechat.official_account');
    
    return $app->jssdk->buildConfig(['getLocation'], TRUE);
  }
  
  /**
   * 地理位置逆编码
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Psr\Http\Message\ResponseInterface|string
   */
  public function geocoder(Request $request) {
    $location = $request->exists("location") ? $request->get("location") : NULL;
    if (!$location) {
      return "缺少参数";
    }
    $client = new Client();
    $res    = $client->get("http://api.map.baidu.com/geocoder/v2/?location=$location&output=json&pois=1&ak=" . env("AK"));
    return $res;
  }
  
  public function getCityByShopId($id) {
    return Shop::find($id)["shop_location"];
  }
}
