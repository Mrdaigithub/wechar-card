<?php
/**
 * Created by PhpStorm.
 * User: dai
 * Date: 2019/1/22
 * Time: 16:36
 */

namespace App\Http\Requests;

use App\Utils\ResponseMessage;
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
            "real_name.regex"  => ResponseMessage::$message[400005],
            "phone.regex"      => ResponseMessage::$message[400006],
            "shop_id.numeric"  => ResponseMessage::$message[400002],
            "identity.numeric" => ResponseMessage::$message[400002],
            "state.numeric"    => ResponseMessage::$message[400002],
            "remarks.numeric"  => ResponseMessage::$message[400005],
        ];
    }
}
