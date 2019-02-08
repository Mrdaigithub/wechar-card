<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\UpdateSystemConfigRequest;
use App\Model\SystemConfig;
use App\Utils\ResponseMessage;
use function PHPSTORM_META\map;
use Tymon\JWTAuth\Facades\JWTAuth;

class SystemConfigController extends ApiController {

    private $oneself;

    public function __construct() {
        $this->oneself = JWTAuth::parseToken()->authenticate();
    }

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
     * @param                                              $id
     * @param \App\Http\Requests\UpdateSystemConfigRequest $request
     *
     * @return mixed
     */
    public function updateSystemConfig($id, UpdateSystemConfigRequest $request) {
        if ($notAdmin = $this->isAdmin($this->oneself)) {
            return $notAdmin;
        }

        $systemConfig = SystemConfig::find($id);
        if ( ! $systemConfig) {
            return $this->notFound(NULL, ResponseMessage::$message[400010]);
        }

        $systemConfig->config_value = $request->get("config_value");
        $this->saveModel($systemConfig);

        return $systemConfig;
    }
}
