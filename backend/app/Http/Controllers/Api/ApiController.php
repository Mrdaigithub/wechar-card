<?php
/**
 * Created by PhpStorm.
 * User: dai
 * Date: 2019/1/26
 * Time: 15:05
 */

namespace App\Http\Controllers\Api;


use App\Helpers\Api\ApiResponse;
use App\Http\Controllers\Controller;
use App\Utils\ResponseMessage;

class ApiController extends Controller {

    use ApiResponse;

    /**
     * 存储数据到数据库
     *
     * @param $model
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function saveModel($model) {
        if ( ! $model->save()) {
            return $this->internalServerError();
        }

        return TRUE;
    }

    /**
     * 是否为管理员
     *
     * @param $user
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function isAdmin($user) {
        if ($user->identity !== 3) {
            return $this->forbidden(NULL, ResponseMessage::$message[403000]);
        }

        return FALSE;
    }

    /**
     * 是否为商铺人员
     *
     * @param $user
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function isEmployee($user) {
        if ($user->identity !== 1 || $user->identity !== 2) {
            return $this->forbidden(NULL, ResponseMessage::$message[403000]);
        }

        return FALSE;
    }

    /**
     * 是否为普通用户
     *
     * @param $user
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function isPlainUser($user) {
        if ($user->identity !== 0) {
            return $this->forbidden(NULL, ResponseMessage::$message[403000]);
        }
        return FALSE;
    }
}
