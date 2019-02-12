<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\GetWriteOffQrCodeRequest;
use App\Model\SystemConfig;
use App\Utils\ResponseMessage;
use function PHPSTORM_META\map;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Tymon\JWTAuth\Facades\JWTAuth;

class QrCodeController extends ApiController {
    /**
     * 获取管理员登录的二维码base64编码
     *
     * @return mixed
     */
    public function adminLogin() {
        $expiredTime = strtotime("+ 5 minutes");
        $url         = env("DOMAIN") . "/wechat/authorize?url=" . urlencode(env("DOMAIN") . "/wechat/grant/login/admin?expired_time=$expiredTime");

        return $this->success(
            base64_encode(
                QrCode::encoding('UTF-8')
                    ->format("png")
                    ->size(300)
                    ->generate($url)
            )
        );
    }

    /**
     * 获取添加商铺老板的二维码base64编码
     */
    public function addShopBoss() {
        $expiredTime = strtotime("+ 5 minutes");
        $url         = env("DOMAIN") . "/wechat/authorize?url=" . urlencode(env("DOMAIN") . "/wechat/grant/add/boss?expired_time=$expiredTime");

        return $this->success(
            base64_encode(
                QrCode::encoding('UTF-8')
                    ->format("png")
                    ->size(300)
                    ->generate($url)
            )
        );
    }

    /**
     * 获取添加指定商铺员工的二维码base64编码
     *
     * @param $id
     *
     * @return mixed
     */
    public function addShopEmployee($id) {
        $expiredTime = strtotime("+ 5 minutes");
        $url         = env("DOMAIN") . "/wechat/authorize?url=" . urlencode(env("DOMAIN") . "/wechat/grant/add/employee?expired_time=$expiredTime&shopid=$id");

        return $url;
        return $this->success(
            base64_encode(
                QrCode::encoding('UTF-8')
                    ->format("png")
                    ->size(300)
                    ->generate($url)
            )
        );
    }

    /**
     * 获取卡券核销的二维码base64编码
     *
     * @param \App\Http\Requests\GetWriteOffQrCodeRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function writeOff(GetWriteOffQrCodeRequest $request) {
        // 添加用户手机号码和姓名如果有, 用户如果已填写信息则不需要
        $lotteryNeedsToFillInTheInformation = SystemConfig::where("config_name", "lotteryNeedsToFillInTheInformation")->first()->config_value;
        $oneself                            = JWTAuth::parseToken()->authenticate();

        // 系统设置必须要填写用户信息&&用户之前未填写过信息
        if ((boolean) $lotteryNeedsToFillInTheInformation
            && ( ! $oneself->real_name || ! $oneself->phone)) {
            // 校验参数
            if (( ! $request->has("real_name") || ! $request->has("phone")) ||
                ( ! $request->get("real_name") || ! $request->get("phone"))) {
                return $this->badRequest(NULL, ResponseMessage::$message[400015]);
            } else {
                $oneself->real_name = $request->get("real_name");
                $oneself->phone     = $request->get("phone");
                $this->saveModel($oneself);
            }
        }

        $expiredTime = strtotime("+ 5 minutes");
        $url         = env("DOMAIN") . "/wechat/authorize?url=" . urlencode(env("DOMAIN") . "/wechat/grant/writeoff?expired_time=$expiredTime&card_id=" . $request->get("card_id"));

        return $this->success(
            base64_encode(
                QrCode::encoding('UTF-8')
                    ->format("png")
                    ->size(300)
                    ->generate($url)
            )
        );
    }
}
