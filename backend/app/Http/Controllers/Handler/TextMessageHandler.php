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
    public function handle($payload = NULL) {
        if ( ! array_key_exists("Content", $payload)) {
            return "local";
        }

        $content = $payload["Content"];

        $activities = Activity::where("reply_keyword", $content);
        if ($activities->get()->isNotEmpty() && $activities->first()->shops()->get()->isNotEmpty()) {
            $activity = $activities->first();
            $shop     = $activities->first()->shops()->first();
            $shopId   = $shop->id;

            $url = env("DOMAIN") . "/wechat/authorize?url=" . urlencode(env("DOMAIN") . "/wechat/grant/lottery/user?shopid=$shopId");

            return new News([
                new NewsItem([
                    'title'       => $activity->activity_name,
                    'description' => $activity->activity_description,
                    'url'         => $url,
                    'image'       => $activity->activity_thumbnail,
                ]),
            ]);
        } else {
            return "";
        }
    }
}
