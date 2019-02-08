<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Model\User;
use App\Utils\ResponseMessage;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends ApiController {

    /**
     * 客户通过openid获取token
     *
     * @param $openid
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function getTokenByOpenid($openid) {
        $users = User::where("openid", $openid);

        if ($users->count() <= 0) {
            return $this->badRequest(NULL, ResponseMessage::$message[401000]);
        }

        return $this->success(JWTAuth::fromUser($users->first()));
    }

    /**
     * 客户通过username, password获取token
     *
     * @param $openid
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function getTokenByPassword($openid) {
        $users = User::where("openid", $openid);
        if ($users->count() <= 0) {
            return $this->badRequest(NULL, ResponseMessage::$message[401000]);
        }

        return $this->success(JWTAuth::fromUser($users->first()));
    }

    /**
     * 废止当前token
     */
    public function invalidateToken() {
        JWTAuth::parseToken()->invalidate();

        return $this->success();
    }
}
