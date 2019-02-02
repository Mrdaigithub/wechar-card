<?php
/**
 * Created by PhpStorm.
 * User: dai
 * Date: 2019/1/22
 * Time: 16:35
 */

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}
	
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			"username"    => "required|string",
			"real_name"   => "string",
			"headimgurl"  => "required|string",
			"phone"       => [
				"string",
				"regex:/^(0|86|17951)?(13[0-9]|15[012356789]|166|17[3678]|18[0-9]|14[57])[0-9]{8}$/u"
			],
			"openid"      => "required|string|unique",
			"state"       => "required|boolean",
			"sign_in_num" => "required|numeric",
			"lottery_num" => "required|numeric",
			"identity"    => "required|numeric",
		];
	}
	
	public function messages() {
		return [
			"username.required"    => "400000",
			"username.string"      => "400003",
			"real_name.string"     => "400003",
			"headimgurl.required"  => "400000",
			"headimgurl.string"    => "400003",
			"phone.string"         => "400003",
			'phone.regex'          => '400004',
			"openid.required"      => "400000",
			"openid.string"        => "400003",
			'openid.unique'        => '400006',
			"state.required"       => "400000",
			"state.boolean"        => "400003",
			"sign_in_num.required" => "400000",
			"sign_in_num.numeric"  => "400003",
			"lottery_num.required" => "400000",
			"lottery_num.numeric"  => "400003",
			"identity.required"    => "400000",
			"identity.numeric"     => "400003",
		];
	}
}
