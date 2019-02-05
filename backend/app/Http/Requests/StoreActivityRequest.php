<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest {

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
            "activity_description" => ["regex:/^(\w|[\x{4e00}-\x{9fa5}])+$/u"],
            "activity_thumbnail"   => "nullable|url",
            "reply_keyword"        => ["required", "string", "regex:/^(\w|[\x{4e00}-\x{9fa5}])+$/u"],
            "remarks"              => ["nullable", "regex:/^(\w|[\x{4e00}-\x{9fa5}])+$/u"],
            "state"                => "boolean",
            "card_model_list"      => "nullable|array",
        ];
    }

    public function messages() {
        return [
            "activity_name.required"     => "必须有活动名参数",
            "activity_name.string"       => "活动名数据类型不正确",
            "activity_name.regex"        => "活动名含有非法字符",
            "activity_description.regex" => "活动详情含有非法字符",
            "activity_thumbnail.regex"   => "活动缩略图格式不正确",
            "reply_keyword.required"     => "必须有活动回复关键词",
            "reply_keyword.string"       => "活动回复关键词数据类型不正确",
            "reply_keyword.regex"        => "活动回复关键词含有非法字符",
            "remarks.regex"              => "活动备注含有非法字符",
            "state.boolean"              => "活动状态数据类型不正确",
            "card_model_list.array"      => "活动对应卡券列表数据类型不正确",
        ];
    }
}
