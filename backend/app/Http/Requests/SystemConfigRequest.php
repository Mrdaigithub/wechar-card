<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SystemConfigRequest extends FormRequest {
  
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize() {
    return TRUE;
  }
  
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules() {
    return [
      "config_name" => "required",
      //      "config_description" => "required",
      //      "config_value"       => "required",
    ];
  }
  
  public function messages() {
    return [
      //      "config_name.required"        => "参数缺失",
      //      "config_description.required" => "参数缺失",
      //      "config_value.required"       => "参数缺失",
    ];
  }
}
