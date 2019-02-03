<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCardModelRequest extends FormRequest {

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
      "card_name"      => ["string", "regex:/^(\w|[\x{4e00}-\x{9fa5}])+$/u",],
      "card_thumbnail" => "string|url",
      "end_time_0"     => "date",
      "end_time_1"     => "numeric",
      "probability"    => "numeric",
      "remarks"        => ["nullable", "regex:/^(\w|[\x{4e00}-\x{9fa5}])+$/u"],
      "state"          => "boolean",
      "type"           => "boolean",
    ];
  }

  public function messages() {
    return [
      "card_name.string"      => "卡券名数据类型不正确",
      "card_name.regex"       => "卡券名格式不正确",
      "card_thumbnail.string" => "卡券名缩略图数据类型不正确",
      "card_thumbnail.url"    => "卡券名缩略图格式不正确",
      "end_time_0.date"       => "卡券定期时间格式不正确",
      "end_time_1.numeric"    => "卡券名倒计时间格式不正确",
      "probability.numeric"   => "卡券名概率格式不正确",
      "remarks.string"        => "卡券备注数据类型不正确",
      "remarks.regex"         => "卡券备注格式不正确",
      "state.boolean"         => "卡券状态数据类型不正确",
      "type.boolean"          => "卡券类型数据类型不正确",
    ];
  }
}
