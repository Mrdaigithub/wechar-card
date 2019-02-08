<?php
/**
 * Created by PhpStorm.
 * User: mrdai
 * Date: 2019-02-08
 * Time: 20:38
 */

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Utils\ResponseMessage;

class WebController extends Controller {
    protected function saveModel($model) {
        if ( ! $model->save()) {
            return response(ResponseMessage::$message[500001]);
        }

        return TRUE;
    }

    protected function response($message) {
        return "<script>alert('$message');document.write('<h1 style=\'text-align:center\'>$message</h1>')</script>";
    }

    protected function getOneself() {
        $app = app('wechat.official_account');

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
}
