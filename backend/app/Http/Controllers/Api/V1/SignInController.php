<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Model\SignIn;
use App\Model\SystemConfig;
use App\Model\User;
use mysql_xdevapi\Collection;
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
     * 清除其他月份的签到记录
     *
     * @param $signInLogList
     *
     * @return array
     */
    private function clearLastMonthSignInLog($signInLogList) {
        return collect($signInLogList)->filter(function ($item) {
            return date("m", time()) == date("m", strtotime($item));
        })->values()->all();
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
        } else {
            $user = User::find($id);
            if ( ! $user) {
                return $this->notFound(NULL, "未找到此用户");
            }
        }

        // 获取签到记录
        $signInLogs     = $user->signInLogs()->first()["month_sign_in_log"];
        $signInLogArray = $signInLogs ? explode(",", $signInLogs) : [];
        // 如果是有上个月的记录则清除
        $signInLogArray = $this->clearLastMonthSignInLog($signInLogArray);

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
        $user           = NULL;
        $signInLog      = NULL;
        $signInLogArray = [];
        $today          = date("Y-m-d", time());
        // 签到达成天数
        $daysCount = (integer) SystemConfig::where("config_name", "howManyDaysHaveYouWonTheLotteryIn15Days")->first()->config_value;

        if ($id == 0) {
            $user = $this->oneself;
        } else {
            $user = User::find($id);
            if ( ! $user) {
                return $this->notFound(NULL, "未找到此用户");
            }
        }

        // 获取签到记录数组
        $signInLogs = $user->signInLogs();
        if ($signInLogs->count() > 0) {
            $signInLogId    = $signInLogs->first()->id;
            $signInLog      = SignIn::find($signInLogId);
            $signInLogArray = $signInLog["month_sign_in_log"] ? explode(",",
                $signInLog["month_sign_in_log"]) : [];
        } else {
            $signInLog = new SignIn();
        }

        // 如果是有上个月的记录则清除
        $signInLogArray = $this->clearLastMonthSignInLog($signInLogArray);

        // 今天是否签到
        if (in_array($today, $signInLogArray, TRUE)) {
            return $this->badRequest(NULL, "今日已经签到过了, 明天再来");
        }
        array_push($signInLogArray, $today);

        // 判断1-15号或16-30,31号是否签到指定天数
        $count = 0;
        if ((integer) date("d", strtotime($today)) <= 15) {
            foreach ($signInLogArray as $item) {
                if (date("d", strtotime($item)) <= 15) {
                    $count++;
                }
            }
        } else {
            foreach ($signInLogArray as $item) {
                if (date("d", strtotime($item)) > 15) {
                    $count++;
                }
            }
        }

        // 更新签到记录
        $signInLog->month_sign_in_log = implode(",", $signInLogArray);
        $this->save_model($signInLog);

        // 如果关联新用户第一次签到用户模型和签到模型
        if ($signInLogs->count() <= 0) {
            $user->signInLogs()->attach($signInLog->id);
        }

        if ($count < $daysCount) {
            return $this->success(explode(",", $signInLog->month_sign_in_log), "签到成功,请再接再厉");
        } elseif ($count == $daysCount) {
            $user->lottery_num++;
            $this->save_model($user);

            return $this->success(explode(",", $signInLog->month_sign_in_log), "签到成功,抽奖次数+1");
        } else {
            return $this->success(explode(",", $signInLog->month_sign_in_log), "签到成功,请再接再厉");
        }
    }
}
