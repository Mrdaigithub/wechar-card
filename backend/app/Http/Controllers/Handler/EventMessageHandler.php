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
        if ($payload['Event'] == "subscribe") {
            return "欢迎关注";
        }
    }
}
