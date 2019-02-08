<?php

namespace App\Http\Requests;

use App\Utils\ResponseMessage;
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
            "card_id.required" => ResponseMessage::$message[400000],
            "card_id.numeric"  => ResponseMessage::$message[400002],
            "card_id.exists"   => ResponseMessage::$message[400001],
            "real_name.regex"  => ResponseMessage::$message[400005],
            "real_name.string" => ResponseMessage::$message[400002],
            "phone.regex"      => ResponseMessage::$message[400023],
            "phone.string"     => ResponseMessage::$message[400024],
        ];
    }
}
