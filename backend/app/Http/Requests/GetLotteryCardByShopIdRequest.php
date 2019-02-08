<?php

namespace App\Http\Requests;

use App\Utils\ResponseMessage;
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
            "location.required" => ResponseMessage::$message[400020],
            "location.string"   => ResponseMessage::$message[400021],
            "location.regex"    => ResponseMessage::$message[400022],
        ];
    }
}
