<?php

namespace App\Http\Requests;

use App\Utils\ResponseMessage;
use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityRequest extends FormRequest {

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
            "activity_name"        => ["required", "string", "regex:/^(\w|[\x{4e00}-\x{9fa5}])+$/u"],
            "activity_description" => ["nullable", "regex:/^(\w|[\x{4e00}-\x{9fa5}])+$/u"],
            "activity_thumbnail"   => "string|url",
            "reply_keyword"        => ["required", "string", "regex:/^(\w|[\x{4e00}-\x{9fa5}])+$/u"],
            "remarks"              => ["nullable", "regex:/^(\w|[\x{4e00}-\x{9fa5}])+$/u"],
            "state"                => "boolean",
            "info_state"           => "boolean",
            "card_model_id_list"   => "nullable|array",
        ];
    }

    public function messages() {
        return [
            "activity_name.required"     => ResponseMessage::$message[400000],
            "activity_name.string"       => ResponseMessage::$message[400002],
            "activity_name.regex"        => ResponseMessage::$message[400005],
            "activity_description.regex" => ResponseMessage::$message[400005],
            "activity_thumbnail.string"  => ResponseMessage::$message[400002],
            "activity_thumbnail.regex"   => ResponseMessage::$message[400006],
            "reply_keyword.required"     => ResponseMessage::$message[400000],
            "reply_keyword.string"       => ResponseMessage::$message[400002],
            "reply_keyword.regex"        => ResponseMessage::$message[400005],
            "remarks.regex"              => ResponseMessage::$message[400005],
            "state.boolean"              => ResponseMessage::$message[400002],
            "info_state.boolean"         => ResponseMessage::$message[400002],
            "card_model_id_list.array"   => ResponseMessage::$message[400002],
        ];
    }
}
