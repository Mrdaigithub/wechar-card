<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Model\SystemConfig;
use function PHPSTORM_META\map;

class SystemConfigController extends ApiController {

  /**
   * 获取配置列表
   *
   * @return mixed
   */
  public function list() {
    return $this->success(SystemConfig::all());
  }
}
