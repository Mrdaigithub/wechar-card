<?php
/**
 * Created by PhpStorm.
 * User: mrdai
 * Date: 2019-02-08
 * Time: 20:38
 */

namespace App\Http\Controllers;

use App\Events\AdminAddBossEvent;
use App\Events\AdminLoginEvent;
use App\Events\MessageEvent;
use App\Utils\ResponseMessage;
use EasyWeChat\Factory;
use GuzzleHttp\Client;
use Overtrue\Socialite\SocialiteManager as Socialite;

class WebController extends Controller {
    /**
     * @param $model
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function saveModel($model) {
        if ( ! $model->save()) {
            return response(ResponseMessage::$message[500001]);
        }

        return TRUE;
    }

    /**
     * @param $message
     *
     * @return string
     */
    protected function response($message) {
        return "<script>alert('$message');document.write('<h1 style=\'text-align:center\'>$message</h1>')</script>";
    }

    /**
     * @return mixed
     */
    protected function getOneself() {
        $app = $this->getOfficialAccount1();

        return $app->oauth->user();
    }

    /**
     * 发送广播信号
     *
     * @param $data
     *
     * @return \Illuminate\Broadcasting\PendingBroadcast
     */
    protected function sendBroad($data) {
        return broadcast(new MessageEvent(json_encode($data)));
    }

    /**
     * 发送管理员登录广播信号
     *
     * @param $openid
     *
     * @return \Illuminate\Broadcasting\PendingBroadcast
     */
    protected function sendAdminLoginBroad($openid) {
        return broadcast(new AdminLoginEvent($openid));
    }

    /**
     * 发送管理员添加老板信号
     *
     * @param $adminId
     *
     * @return \Illuminate\Broadcasting\PendingBroadcast
     */
    protected function sendAdminAddBossBroad($adminId) {
        return broadcast(new AdminAddBossEvent($adminId));
    }

    /**
     * 是否为管理员
     *
     * @param $user
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function isAdmin($user) {
        if ($user->identity !== 3) {
            return $this->response(ResponseMessage::$message[403000]);
        }

        return FALSE;
    }

    /**
     * 是否为商铺老板
     *
     * @param $user
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function isBoss($user) {
        if ($user->identity !== 1) {
            return $this->response(ResponseMessage::$message[403000]);
        }

        return FALSE;
    }

    /**
     * 是否为商铺员工老板
     *
     * @param $user
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function isEmployee($user) {
        if ($user->identity !== 1 || $user->identity !== 2) {
            return $this->response(ResponseMessage::$message[403000]);
        }

        return FALSE;
    }

    /**
     * 是否为普通用户
     *
     * @param $user
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function isPlainUser($user) {
        if ($user->identity !== 0) {
            return $this->response(ResponseMessage::$message[403000]);
        }

        return FALSE;
    }

    /**
     * 获取服务号实例
     *
     * @return \EasyWeChat\OfficialAccount\Application
     */
    protected function getOfficialAccount1() {
        return Factory::officialAccount([
            'app_id'        => env("WECHAT_OFFICIAL_ACCOUNT_APPID_1", ""),
            'secret'        => env("WECHAT_OFFICIAL_ACCOUNT_SECRET_1", ""),
            'response_type' => 'array',
        ]);
    }

    /**
     * 发起GET请求
     *
     * @param $url
     *
     * @return string
     */
    protected function sendGetRequest($url) {
        return (string) (new Client())->get($url)->getBody();
    }
}
