<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Model\SystemConfig;
use function PHPSTORM_META\map;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
}
