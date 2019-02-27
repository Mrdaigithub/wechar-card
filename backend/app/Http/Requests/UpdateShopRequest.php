<?php

namespace App\Http\Requests;

use App\Utils\ResponseMessage;
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
            "started_at"    => "nullable|date",
            "activity_id"   => "nullable|numeric",
            "remarks"       => ["nullable", "regex:/^(\w|[\x{4e00}-\x{9fa5}])+$/u"],
            "state"         => "boolean",
        ];
    }

    public function messages() {
        return [
            "shop_name.string"     => ResponseMessage::$message[400002],
            "shop_name.regex"      => ResponseMessage::$message[400006],
            "shop_location.string" => ResponseMessage::$message[400002],
            "shop_location.regex"  => ResponseMessage::$message[400006],
            "started_at.date"      => ResponseMessage::$message[400006],
            "activity_id.numeric"  => ResponseMessage::$message[400002],
            "remarks.regex"        => ResponseMessage::$message[400006],
            "state.boolean"        => ResponseMessage::$message[400002],
        ];
    }
}
