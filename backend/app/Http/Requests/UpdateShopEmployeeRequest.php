<?php
/**
 * Created by PhpStorm.
 * User: dai
 * Date: 2019/1/22
 * Time: 16:36
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShopEmployeeRequest extends FormRequest {

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
            "real_name" => ["nullable", "regex:/^(\w|[\x{4e00}-\x{9fa5}])+$/u"],
            "phone"     => ["nullable", "regex:/^(0|86|17951)?(13[0-9]|15[012356789]|166|17[3678]|18[0-9]|14[57])[0-9]{8}$/u"],
            "shop_id"   => "nullable|numeric",
            "identity"  => "nullable|numeric",
            "state"     => "nullable|numeric",
            "remarks"   => ["nullable", "regex:/^(\w|[\x{4e00}-\x{9fa5}])+$/u"],
        ];
    }

    public function messages() {
        return [
            "real_name.regex"  => "姓名含有非法字符",
            "phone.regex"      => "手机号格式不正确",
            "shop_id.numeric"  => "商铺id数据类型不正确",
            "identity.numeric" => "身份数据类型不正确",
            "state.numeric"    => "启用状态数据类型不正确",
            "remarks.numeric"  => "备注含有非法字符",
        ];
    }
}
