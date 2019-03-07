<?php

namespace App\Http\Requests;

use App\Utils\ResponseMessage;
use Illuminate\Foundation\Http\FormRequest;

class StoreCardModelRequest extends FormRequest {

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
            "card_name"      => [
                "required",
                "string",
                "regex:/^(\w|[\x{4e00}-\x{9fa5}]|\/)+$/u",
            ],
            "card_thumbnail" => "string|url",
            "end_time_0"     => "date",
            "end_time_1"     => "numeric",
            "probability"    => "numeric",
            "remarks"        => ["nullable", "regex:/^(\w|[\x{4e00}-\x{9fa5}]|\/)+$/u"],
            "state"          => "boolean",
            "type"           => "boolean",
        ];
    }

    public function messages() {
        return [
            "card_name.required"    => ResponseMessage::$message[400000],
            "card_name.string"      => ResponseMessage::$message[400002],
            "card_name.regex"       => ResponseMessage::$message[400006],
            "card_thumbnail.string" => ResponseMessage::$message[400002],
            "card_thumbnail.url"    => ResponseMessage::$message[400006],
            "end_time_0.date"       => ResponseMessage::$message[400006],
            "end_time_1.numeric"    => ResponseMessage::$message[400006],
            "probability.numeric"   => ResponseMessage::$message[400006],
            "remarks.regex"         => ResponseMessage::$message[400006],
            "state.boolean"         => ResponseMessage::$message[400002],
            "type.boolean"          => ResponseMessage::$message[400002],
        ];
    }
}
