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
use App\Model\Card;
use App\Model\Shop;
use App\Model\User;
use App\Utils\ResponseMessage;
use EasyWeChat\Kernel\Messages\Message;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class WeChatController extends WebController {

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
     * 微信授权获取用户信息,跳转指定页面
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function wechatAuthorize(Request $request) {
        if ( ! $request->exists("url")) {
            return $this->response(ResponseMessage::$message[400000]);
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
        $user = $this->getOneself();

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
        $wechatUser = $this->getOneself();

        $userList = User::where("openid", $wechatUser->getId())->get();
        if ($userList->isEmpty()) {
            return $this->response(ResponseMessage::$message[400007]);
        } elseif ($userList->first()->identity !== 3) {
            return $this->response(ResponseMessage::$message[403000]);
        }

        // 通过验证发送消息
        $this->sendBroad([
            "signal" => "allowLogin",
            "openid" => $wechatUser->getId(),
        ]);

        return $this->response(ResponseMessage::$message[200001]);
    }

    /**
     * 认证通过发送允许添加商铺老板的消息
     *
     * @return string
     */
    public function grantAddBoss() {
        $app        = app('wechat.official_account');
        $openid     = $this->getOneself()->getId();
        $wechatUser = $app->user->get($openid);

        // 用户认证
        $userList = User::where("openid", $wechatUser["openid"])->get();
        if ($userList->isNotEmpty()) {
            return $this->response(ResponseMessage::$message[400007]);
        }

        $user               = new User;
        $user->openid       = $wechatUser["openid"];
        $user->username     = $wechatUser["nickname"];
        $user->head_img_url = $wechatUser["headimgurl"];
        $user->identity     = 1;
        $this->saveModel($user);

        // 通过验证发送消息
        $this->sendBroad([
            "signal" => "allowAddBoss",
        ]);

        return $this->response(ResponseMessage::$message[200002]);
    }

    /**
     * 认证通过店员信息并进行核销操作
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function grantWriteOff(Request $request) {
        $user = $this->getOneself();

        if ( ! $request->has("card_id") || ! $request->get("card_id")) {
            return $this->response(ResponseMessage::$message[400009]);
        }

        // 用户认证确认是否为店员老板
        $writeOffer = User::where("openid", $user->getId())->first();
        if ( ! $writeOffer) {
            return $this->response(ResponseMessage::$message[400008]);
        }
        if ($writeOffer->identity !== 1 && $writeOffer->identity !== 2) {
            return $this->response(ResponseMessage::$message[403000]);
        }

        // 卡券是否存在
        $card = Card::find($request->get("card_id"));
        if ( ! $card || $card->type === 0 || ! $card->parentid) {
            return $this->response(ResponseMessage::$message[500002]);
        }

        // 是否为本店的人员
        $cardModelActivity = Card::find($card->parentid)->activity();
        if ($cardModelActivity->get()->isEmpty()) {
            return $this->response(ResponseMessage::$message[500004]);
        }
        $cardModelActivityShop = $cardModelActivity->first()->shops();
        if ($cardModelActivityShop->get()->isEmpty()) {
            return $this->response(ResponseMessage::$message[500004]);
        }
        $writeOfferShop = $writeOffer->shop();
        if ($writeOfferShop->get()->isEmpty()) {
            return $this->response(ResponseMessage::$message[500004]);
        }
        if ($writeOfferShop->first()->id !== $cardModelActivityShop->first()->id) {
            return $this->response(ResponseMessage::$message[403000]);
        }

        // 卡券中奖记录
        $winningLog = $card->winningLog()->first();
        if ( ! $winningLog) {
            return $this->response(ResponseMessage::$message[500002]);
        }
        if ((boolean) $winningLog->write_off_state) {
            return $this->response(ResponseMessage::$message[500003]);
        }

        // 卡券所属用户
        $client = $winningLog->user();
        if ($client->get()->isEmpty()) {
            return $this->response(ResponseMessage::$message[500004]);
        }

        $card->state = FALSE;
        $this->saveModel($card);

        $winningLog->write_off_state = TRUE;
        $winningLog->write_off_date  = date('Y-m-d h:i:s', time());
        $this->saveModel($winningLog);

        $winningLog->writeOffer()->attach($writeOffer->id);

        // 通过发送核销成功消息
        $this->sendBroad([
            "signal"  => "writeOff",
            "user_id" => $client->first()->id,
        ]);

        return $this->response(ResponseMessage::$message[200003]);
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
            return $this->response(ResponseMessage::$message[400000]);
        }
        $client = new Client();
        $res    = $client->get("http://api.map.baidu.com/geocoder/v2/?location=$location&output=json&pois=1&ak=" . env("AK"));

        return $res;
    }

    /**
     * 通过商铺id获取城市名
     *
     * @param $id
     *
     * @return mixed
     */
    public function getCityByShopId($id) {
        return Shop::find($id)["shop_location"];
    }
}
