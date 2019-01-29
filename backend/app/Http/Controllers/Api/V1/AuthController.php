<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Model\User;
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
      return $this->badRequest(NULL, "认证错误");
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
