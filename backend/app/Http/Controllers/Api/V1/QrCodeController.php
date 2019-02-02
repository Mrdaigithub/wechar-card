<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Model\SystemConfig;
use function PHPSTORM_META\map;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
}
