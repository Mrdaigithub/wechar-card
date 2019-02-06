<?php
/**
 * Created by PhpStorm.
 * User: mrdai
 * Date: 2018-12-21
 * Time: 00:52
 */

namespace App\Http\Controllers\Handler;


use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Model\User;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;

class EventMessageHandler extends Controller implements EventHandlerInterface {
    /**
     * @param mixed $payload
     *
     * @return string
     */
    public function handle($payload = NULL) {
        $app    = app('wechat.official_account');
        $openid = $payload["FromUserName"];

        if ($payload['Event'] == "subscribe") {
            if (User::where("openid", $openid)->count() <= 0) {
                $wechat_user = $app->user->get($openid);

                $user               = new User;
                $user->openid       = $wechat_user["openid"];
                $user->username     = $wechat_user["nickname"];
                $user->head_img_url = $wechat_user["headimgurl"];
                $user->lottery_num  = 1;
                $user->save();

                return "欢迎首次关注";
            }

            return "欢迎回来";
        }
    }
}
