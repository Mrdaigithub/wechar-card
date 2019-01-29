<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Model\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends ApiController {

  /**
   * 获取用户列表
   *
   * @return \Illuminate\Database\Eloquent\Collection|static[]
   */
  public function list() {
    return $this->success(User::all());
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\Response
   */
  public function save(Request $request) {
    //
  }

  /**
   * 通过 id 查找用户
   *
   * @param $id
   *
   * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
   */
  public function get_user_by_id($id) {
    if ($id == 0) {
      return $this->success(JWTAuth::parseToken()->authenticate());
    }
    $user = User::find($id);
    if (!$user) {
      return $this->notFound(NULL, "未找到此用户");
    }
    return $this->success($user);
  }

  /**
   * 更新用户信息
   *
   * @param  \Illuminate\Http\Request $request
   * @param  int                      $id
   *
   * @return void
   */
  public function update(Request $request, $id) {
    //
  }

  /**
   * 删除指定id用户
   *
   * @param  int $id
   *
   * @return \Illuminate\Http\Response
   */
  public function remove($id) {
    //
  }
}
