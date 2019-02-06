<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetLotteryCardByShopIdRequest extends FormRequest {

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
            "location" => ["required", "string", "regex:/^([\x{4e00}-\x{9fa5}])+$/u"],
        ];
    }

    public function messages() {
        return [
            "location.required" => "必须有地址参数",
            "location.string"   => "地址数据类型不正确",
            "location.regex"    => "地址含有非法字符",
        ];
    }
}
