<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\SystemConfigRequest;
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
  
  /**
   * 更新系统配置
   *
   * @param $id
   * @param \App\Http\Requests\SystemConfigRequest $request
   *
   * @return mixed
   */
  public function updateSystemConfig($id, SystemConfigRequest $request) {
    return 1;
    return $request;
    $systemConfig = SystemConfig::find($id);
    if (!$systemConfig) {
      return $this->notFound(NULL, "未找到此系统配置项");
    }
    
  }
}
