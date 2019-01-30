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

class ApiController extends Controller {
  
  use ApiResponse;
  
  protected function save_model($model) {
    if (!$model->save()) {
      return $this->internalServerError();
    }
    return TRUE;
  }
}
