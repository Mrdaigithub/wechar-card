<?php
/**
 * Created by PhpStorm.
 * User: mrdai
 * Date: 2018-12-19
 * Time: 21:06
 */

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Http\Controllers\Handler\EventMessageHandler;
use App\Http\Controllers\Handler\TextMessageHandler;
use App\Model\Shop;
use App\Model\User;
use EasyWeChat\Kernel\Messages\Message;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class WeChatController extends Controller {

    public function test() {
    }

    /**
     * 微信授权获取用户信息,跳转指定页面
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function wechatAuthorize(Request $request) {
        if ( ! $request->exists("url")) {
            return "参数错误";
        }
        $url = $request->get("url");

        $app      = app('wechat.official_account');
        $response = $app->oauth->scopes(['snsapi_base'])->redirect($url);

        return $response;
    }

    /**
     * 普通用户认证跳转到地理位置验证界面通过则跳转到抽奖界面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function grantLotteryUser() {
        $app  = app('wechat.official_account');
        $user = $app->oauth->user();

        return view("redirectUserLottery", [
            "openid" => $user->getId(),
            "url"    => env("FRONT_DOMAIN") . "/user/lottery",
        ]);
    }

    /**
     * 认证通过发送允许登录管理界面的消息
     *
     * @return string
     */
    public function grantLoginAdmin() {
        $app        = app('wechat.official_account');
        $wechatUser = $app->oauth->user();

        $userList = User::where("openid", $wechatUser->getId())->get();
        if ($userList->isEmpty()) {
            return "用户不存在";
        } elseif ($userList->first()->identity !== 3) {
            return "无权限";
        }

        // 通过验证发送消息
        broadcast(new MessageEvent(json_encode([
            "signal" => "allowLogin",
            "openid" => $wechatUser->getId(),
        ])));

        return "登录成功";
    }

    /**
     * 认证通过发送允许添加商铺老板的消息
     *
     * @return string
     */
    public function grantAddBoss() {
        $app        = app('wechat.official_account');
        $openid     = $app->oauth->user()->getId();
        $wechatUser = $app->user->get($openid);

        // 用户认证
        $userList = User::where("openid", $wechatUser["openid"])->get();
        if ($userList->isNotEmpty()) {
            return "用户已存在";
        }

        $user               = new User;
        $user->openid       = $wechatUser["openid"];
        $user->username     = $wechatUser["nickname"];
        $user->head_img_url = $wechatUser["headimgurl"];
        $user->identity     = 1;
        $user->save();

        // 通过验证发送消息
        broadcast(new MessageEvent(json_encode([
            "signal" => "allowAddBoss",
        ])));

        return "添加成功";
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
        if ( ! $location) {
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
