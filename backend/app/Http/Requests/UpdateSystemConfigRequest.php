<?php

namespace App\Http\Requests;

use App\Utils\ResponseMessage;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSystemConfigRequest extends FormRequest {

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
            "config_value" => "required",
        ];
    }

    public function messages() {
        return [
            "config_value.required" => ResponseMessage::$message[400000],
        ];
    }
}
