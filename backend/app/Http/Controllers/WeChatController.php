<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Handler\EventMessageHandler;
use App\Http\Controllers\Handler\TextMessageHandler;
use App\Model\Card;
use App\Model\Shop;
use App\Model\User;
use App\Utils\ResponseMessage;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Message;
use Illuminate\Http\Request;

class WeChatController extends WebController {
    public function test() {
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
     * 微信授权获取用户openid,跳转指定页面
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function wechatBaseAuthorize(Request $request) {
        if ( ! $request->exists("url")) {
            return $this->response(ResponseMessage::$message[400000]);
        }

        $app = $this->getOfficialAccount1();

        return $app->oauth->scopes(["snsapi_base"])->redirect($request->get("url"));
    }

    /**
     * 普通用户认证openid跳转到地理位置验证界面通过则跳转到抽奖界面
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View|mixed
     */
    public function grantLotteryUserOpenid(Request $request) {
        if ( ! $request->exists("shopid")) {
            return $this->response(ResponseMessage::$message[400000]);
        }
        $shop = Shop::find($request->get("shopid"));
        if ( ! $shop) {
            return $this->response(ResponseMessage::$message[400028]);
        }

        $users = User::where("openid", $this->getOneself()->getId());
        if ($users->get()->isEmpty()) {
            return $this->getOfficialAccount1()->oauth
                ->scopes(["snsapi_userinfo"])
                ->redirect(env("DOMAIN") .
                    "/wechat/grant/lottery/user/info?shopid=" . $request->get("shopid"));
        }
        $user = $users->first();
        if ($res = $this->isPlainUser($user)) {
            return $res;
        }

        return view("redirectUserLottery", [
            "openid"       => $user->openid,
            "url"          => env("FRONT_DOMAIN") . "/user/lottery",
            "shopLocation" => $shop->shop_location,
        ]);
    }

    /**
     * 普通用户认证openid跳转到地理位置验证界面通过则跳转到抽奖界面
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View|mixed
     */
    public function grantLotteryUserInfo(Request $request) {
        if ( ! $request->exists("shopid")) {
            return $this->response(ResponseMessage::$message[400000]);
        }
        $shop = Shop::find($request->get("shopid"));
        if ( ! $shop) {
            return $this->response(ResponseMessage::$message[400028]);
        }

        $wechatUser = $this->getOneself();
        $users      = User::where("openid", $wechatUser->getId());
        if ($users->get()->isEmpty()) {
            $user               = new User;
            $user->openid       = $wechatUser->getId();
            $user->username     = $wechatUser->getNickname();
            $user->head_img_url = $wechatUser->getAvatar();
            $user->lottery_num  = 1;

            $user->save();
        } else {
            $user = $users->first();
            if ($res = $this->isPlainUser($user)) {
                return $res;
            }
        }

        return view("redirectUserLottery", [
            "openid"       => $user->openid,
            "url"          => env("FRONT_DOMAIN") . "/user/lottery",
            "shopLocation" => $shop->shop_location,
        ]);
    }

    /**
     * 认证通过跳转到商铺管理界面
     *
     * @return string
     */
    public function grantToShop() {
        $wechatUser = $this->getOneself();

        $users = User::where("openid", $wechatUser->getId())->get();
        if ($users->isEmpty()) {
            return $this->response(ResponseMessage::$message[400007]);
        } elseif ($isBoss = $this->isBoss($users->first())) {
            return $isBoss;
        }

        return view("redirectShop", [
            "openid" => $wechatUser->getId(),
            "url"    => env("FRONT_DOMAIN") . "/shop",
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
        $this->sendAdminLoginBroad($wechatUser->getId());

        return $this->response(ResponseMessage::$message[200001]);
    }

    /**
     * 认证通过发送允许添加商铺老板的消息
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function grantAddShopBoss(Request $request) {
        if ( ! $request->has("admin_id") || $request->get("admin_id") === "") {
            return $this->response(ResponseMessage::$message[400000]);
        }

        $wechatUser = $this->getOneself();

        // 用户认证
        $user = User::where("openid", $wechatUser->getId());
        if ($user->get()->isNotEmpty()) {
            $user           = $user->first();
            $user->identity = 1;
        } else {
            $user               = new User;
            $user->openid       = $wechatUser->getId();
            $user->username     = $wechatUser->getNickname();
            $user->head_img_url = $wechatUser->getAvatar();
            $user->identity     = 1;
        }
        $this->saveModel($user);

        // 通过验证发送消息
        $this->sendAdminAddBossBroad($request->get("admin_id"));

        return $this->response(ResponseMessage::$message[200002]);
    }

    /**
     * 认证通过发送允许添加商铺员工的消息
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function grantAddShopEmployee(Request $request) {
        $shopId = $request->has("shopid") ? $request->get("shopid") : NULL;
        if ( ! $shopId) {
            return $this->response(ResponseMessage::$message[400000]);
        }

        $app        = app('wechat.official_account');
        $openid     = $this->getOneself()->getId();
        $wechatUser = $app->user->get($openid);

        // 用户认证
        $user = User::where("openid", $wechatUser["openid"]);
        if ($user->get()->isNotEmpty()) {
            // 此用户已为其他角色
            $user           = $user->first();
            $user->identity = 2;
        } else {
            // 此用户暂未存在于数据库
            $user               = new User;
            $user->openid       = $wechatUser["openid"];
            $user->username     = $wechatUser["nickname"];
            $user->head_img_url = $wechatUser["headimgurl"];
            $user->identity     = 2;
        }

        $this->saveModel($user);
        $user->shop()->detach();
        $user->shop()->attach($shopId);

        // 通过验证发送消息
        $this->sendBroad([
            "signal" => "allowAddEmployee",
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
            return $this->response(ResponseMessage::$message[500005]);
        }
        $cardModelActivityShop = $cardModelActivity->first()->shops();
        if ($cardModelActivityShop->get()->isEmpty()) {
            return $this->response(ResponseMessage::$message[400025]);
        }
        $writeOfferShop = $writeOffer->shop();
        if ($writeOfferShop->get()->isEmpty()) {
            return $this->response(ResponseMessage::$message[400026]);
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

        return $this->sendGetRequest("http://api.map.baidu.com/geocoder/v2/?location=$location&output=json&pois=1&ak=" . env("AK"));
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
