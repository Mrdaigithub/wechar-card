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
use EasyWeChat\Kernel\Messages\Message;

class WeChatController extends Controller
{

    public function test()
    {

    }

    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        $app = app('wechat.official_account');

        $app->server->push(TextMessageHandler::class, Message::TEXT); // 文本消息
        $app->server->push(EventMessageHandler::class, Message::EVENT); // 事件消息

        return $app->server->serve();
    }
}
