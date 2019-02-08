<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetWriteOffQrCodeRequest extends FormRequest {

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
            "card_id"   => ["required", "numeric", "exists:card,id"],
            "real_name" => ["nullable", "string", "regex:/^(\w|[\x{4e00}-\x{9fa5}])+$/u"],
            "phone"     => ["nullable", "string", "regex:/^(0|86|17951)?(13[0-9]|15[012356789]|166|17[3678]|18[0-9]|14[57])[0-9]{8}$/u"],
        ];
    }

    public function messages() {
        return [
            "card_id.required" => "必须有卡券id参数",
            "card_id.numeric"  => "卡券id数据类型不正确",
            "card_id.exists"   => "卡券id不存在",
            "real_name.regex"  => "名称含有非法字符",
            "real_name.string" => "名称数据类型不正确",
            "phone.regex"      => "手机号码格式不正确",
            "phone.string"     => "手机号码数据类型不正确",
        ];
    }
}
