<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Model\User;
use Illuminate\Http\Request;

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
   * @param  int $id
   *
   * @return \Illuminate\Http\Response
   */
  public function get_user_by_id($id) {
    $user = User::find($id);
    if (!$user) {
      return $this->notFound("未找到此用户");
    }
    return $user;
  }
  
  /**
   * 通过 openid 查找用户
   *
   * @param $openid
   *
   * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   */
  public function get_user_by_openid($openid) {
    $users = User::where("openid", $openid);
    if ($users->count() <= 0) {
      return $this->unauthorized("无效的openid");
    }
    return $users->first();
  }
  
  /**
   * 更新用户信息
   *
   * @param  \Illuminate\Http\Request $request
   * @param  int $id
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
