<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Model\Activity;
use App\Model\Card;
use App\Model\Shop;
use App\Model\SignIn;
use App\Model\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class SignInController extends ApiController {

  private $oneself;

  /**
   * SignInController constructor.
   */
  public function __construct() {
    $this->oneself = JWTAuth::parseToken()->authenticate();
  }

  /**
   * 获取指定用户的签到记录
   *
   * @param $id
   *
   * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
   */
  public function getSignInLogByUserId($id) {
    $signInLogs = NULL;
    $user       = NULL;
    if ($id == 0) {
      $user = $this->oneself;
    }
    else {
      $user = User::find($id);
      if (!$user) {
        return $this->notFound(NULL, "未找到此用户");
      }
    }

    // 获取签到记录
    $signInLogs     = $user->signInLogs()->first()["month_sign_in_log"];
    $signInLogArray = $signInLogs ? explode(",", $signInLogs) : [];
    // 如果是上个月的记录则清除
    if (count($signInLogArray)
        && date("m", strtotime($signInLogArray[count($signInLogArray) - 1]))
           !== date("m", time())) {
      $signInLogArray = [];
    }

    return $this->success($signInLogArray);
  }

  /**
   * 执行签到操作更新记录
   *
   * @param $id
   *
   * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|null
   */
  public function UpdateTodaySignInLogByUserId($id) {
    $user  = NULL;
    $today = date("Y-m-d", time());
    if ($id == 0) {
      $user = $this->oneself;
    }
    else {
      $user = User::find($id);
      if (!$user) {
        return $this->notFound(NULL, "未找到此用户");
      }
    }

    // 获取签到记录
    $signInLogId    = $user->signInLogs()->first()->id;
    $signInLog      = SignIn::find($signInLogId);
    $signInLogArray = $signInLog["month_sign_in_log"] ? explode(",",
      $signInLog["month_sign_in_log"]) : [];
    // 如果是上个月的记录则清除
    if (count($signInLogArray)
        && date("m", strtotime($signInLogArray[count($signInLogArray) - 1]))
           !== date("m", time())) {
      $signInLogArray = [];
    }
    // 今天是否签到
    if (in_array($today, $signInLogArray, TRUE)) {
      return $this->badRequest(NULL, "今日已经签到过了, 明天再来");
    }
    // Todo 15天签到7天+1抽奖次数
    array_push($signInLogArray, $today);
    $signInLog->month_sign_in_log = implode(",", $signInLogArray);
    $signInLog->save();
    return $this->success(explode(",", $signInLog->month_sign_in_log));
  }
}
