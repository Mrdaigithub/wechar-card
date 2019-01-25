<?php
/**
 * Created by PhpStorm.
 * User: mrdai
 * Date: 2018-12-19
 * Time: 21:06
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Handler\EventMessageHandler;
use App\Http\Controllers\Handler\TextMessageHandler;
use App\Model\Activity;
use EasyWeChat\Kernel\Messages\Message;
use Illuminate\Http\Request;

class WeChatController extends Controller {
	public function test() {
		$app = app('wechat.official_account');
		
		return $app->jssdk->buildConfig(array('onMenuShareQQ', 'onMenuShareWeibo'), true);
	}
	
	/**
	 * 处理微信的请求消息
	 *
	 * @return string
	 */
	public function serve() {
		$app = app('wechat.official_account');
		
		$app->server->push(TextMessageHandler::class, Message::TEXT); // 文本消息
		$app->server->push(EventMessageHandler::class, Message::EVENT); // 事件消息
		
		return $app->server->serve();
	}
	
	/**
	 * 获取Access token
	 *
	 * @return mixed
	 */
	public function getAccessToken() {
		$app = app('wechat.official_account');
		
		return $app->access_token->getToken();
	}
	
	/**
	 * 获取js sdk 配置
	 *
	 * @return mixed
	 */
	public function getJsSdkConfig() {
		$app = app('wechat.official_account');
		
		return $app->jssdk->buildConfig(array('getLocation'), true);
	}
	
	public function oauth2_authorization_user(Request $request) {
	}
}
