<?php
/**
 * Created by PhpStorm.
 * User: mrdai
 * Date: 2018-12-21
 * Time: 00:52
 */

namespace App\Http\Controllers\Handler;


use App\Model\Shop;
use App\Model\User;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;

class TextMessageHandler implements EventHandlerInterface
{
    /**
     * @param mixed $payload
     * @return string
     */
    public function handle($payload = null)
    {
        if (!array_key_exists("Content", $payload)) return "local";

        $content = $payload["Content"];
        $openid = $payload["FromUserName"];

        if (Shop::where("shop_name", $content)->count() > 0) {
            $shop_id = Shop::where("shop_name", "123")->get()[0]['id'];
            $user_id = User::where("openid", $openid)->first()['id'];
            return new News([
                new NewsItem([
                    'title' => $content." 的抽奖活动",
                    'description' => 'Good Luck～',
                    'url' => "https://mrdaisite.club/user/lottery?uid=$user_id&shopid=$shop_id",
                    'image' => "http://mmbiz.qpic.cn/mmbiz_jpg/c5vy0YJlTVVy7sfKKe8EQShvKEYaKPnS2wfphdib01B105eVCbMz2Foqficsz2lVacm8dFoukpCcbWyG2iah7vuLA/0?wx_fmt=jpeg",
                ]),
            ]);
        }

        $shop_list = Shop::all();
        $res = "未找到此商铺\n当前合作的商铺有:\n\n";
        foreach ($shop_list as $item) {
            $res .= "( " . $item["shop_name"] . " )\n";
        }
        return $res;
    }
}
