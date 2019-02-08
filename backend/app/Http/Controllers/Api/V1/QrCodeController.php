<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\GetWriteOffQrCodeRequest;
use App\Model\SystemConfig;
use function PHPSTORM_META\map;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Tymon\JWTAuth\Facades\JWTAuth;

class QrCodeController extends ApiController {
    // Todo 添加二维码过期失效

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
                return $this->badRequest(NULL, "请填写姓名与电话再领取卡券");
            } else {
                $oneself->real_name = $request->get("real_name");
                $oneself->phone     = $request->get("phone");
                $this->save_model($oneself);
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
