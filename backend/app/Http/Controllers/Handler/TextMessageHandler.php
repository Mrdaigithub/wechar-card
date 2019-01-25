<?php
/**
 * Created by PhpStorm.
 * User: mrdai
 * Date: 2018-12-21
 * Time: 00:52
 */

namespace App\Http\Controllers\Handler;


use App\Model\Activity;
use App\Model\User;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;

class TextMessageHandler implements EventHandlerInterface {
	/**
	 * @param mixed $payload
	 *
	 * @return string
	 */
	public function handle($payload = null) {
		if( !array_key_exists("Content", $payload)) {
			return "local";
		}
		
		$content = $payload["Content"];
		$openid  = $payload["FromUserName"];
		
		$activities = Activity::where("reply_keyword", $content);
		if($activities->count() > 0 && $activities->first()->shops()->count() > 0) {
			$activity = $activities->first();
			$shop_id  = $activities->first()->shops()->first()->id;
			
			return new News([
				new NewsItem([
					'title'       => $activity->activity_name,
					'description' => $activity->activity_description,
					'url'         => env("DOMAIN") . "/user/lottery?openid=$openid&shopid=$shop_id",
					'image'       => $activity->activity_thumbnail,
				]),
			]);
		}
		
		return "未找到此卡券";
	}
}
