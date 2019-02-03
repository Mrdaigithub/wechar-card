<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShopRequest extends FormRequest {

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
      "shop_name"     => [
        "string",
        "regex:/^(\w|[\x{4e00}-\x{9fa5}])+$/u",
      ],
      "shop_location" => [
        "string",
        "regex:/^([\x{4e00}-\x{9fa5}])+$/u",
      ],
      "started_at"    => "date",
      "activity_id"   => "nullable|numeric",
      "remarks"       => ["nullable", "regex:/^(\w|[\x{4e00}-\x{9fa5}])+$/u"],
      "state"         => "boolean",
    ];
  }

  public function messages() {
    return [
      "shop_name.string"       => "商铺名数据类型不正确",
      "shop_name.regex"        => "商铺名格式不正确",
      "shop_location.string"   => "商铺地址数据类型不正确",
      "shop_location.regex"    => "商铺地址格式不正确",
      "started_at.date"        => "商铺开始合作时间格式不正确",
      "activity_id.numeric"    => "商铺名参加的活动ID数据类型不正确",
      "remarks.regex"          => "商铺备注格式不正确",
      "state.boolean"          => "商铺状态数据类型不正确",
    ];
  }
}
