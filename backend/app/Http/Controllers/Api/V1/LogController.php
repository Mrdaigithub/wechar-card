<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Model\Shop;
use App\Model\WinningLog;
use App\Utils\ResponseMessage;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogController extends ApiController {
    /**
     * 获取中奖核销日志列表
     *
     * @return mixed
     */
    public function listWinningWriteOffLog() {
        return $this->success(
            WinningLog::all()->map(function ($item) {
                $cards       = $item->card();
                $users       = $item->user();
                $activities  = $item->activity();
                $writeOffers = $item->writeOffer();

                $item->card_name      = $cards->get()->isNotEmpty() ? $cards->first()->card_name : NULL;
                $item->activity_name  = $activities->get()->isNotEmpty() ? $activities->first()->activity_name : NULL;
                $item->user_id        = $users->get()->isNotEmpty() ? $users->first()->id : NULL;
                $item->shop_name      = $activities->get()->isNotEmpty() && $activities->first()->shops()->get()->isNotEmpty() ?
                    $activities->first()->shops()->first()->shop_name : NULL;
                $item->write_offer_id = $writeOffers->get()->isNotEmpty() ? $writeOffers->first()->id : NULL;

                return $item;
            })
        );
    }

    /**
     * 获取指定商铺的中奖核销日志列表
     *
     * @param $id
     *
     * @return mixed
     */
    public function listWinningWriteOffLogByShopId($id) {
        $oneself = JWTAuth::parseToken()->authenticate();
        $shop = Shop::find($id);
        if ( ! $shop) {
            return $this->badRequest(NULL, ResponseMessage::$message[400028]);
        }

        $shopId = $oneself->shop()->get()->isNotEmpty() ? $oneself->shop()->first()->id : NULL;
        if ($shopId !== $shop->id) {
            return $this->forbidden(NULL, ResponseMessage::$message[403000]);
        }

        $shopActivity = $shop->activity();
        if ($shopActivity->get()->isEmpty()) {
            return $this->badRequest(NULL, ResponseMessage::$message[400003]);
        }
        $shopActivityWinningLog = $shopActivity->first()->winningLogs();
        if ($shopActivityWinningLog->get()->isEmpty()) {
            return $this->badRequest(NULL, ResponseMessage::$message[400003]);
        }

        return $this->success(
            $shopActivityWinningLog->get()->map(function ($item) {
                $cards       = $item->card();
                $user        = $item->user()->get()->isNotEmpty() ? $item->user()->first() : NULL;
                $activities  = $item->activity();
                $writeOffers = $item->writeOffer();

                $item->card_name      = $cards->get()->isNotEmpty() ? $cards->first()->card_name : NULL;
                $item->activity_name  = $activities->get()->isNotEmpty() ? $activities->first()->activity_name : NULL;
                $item->username       = $user ? $user->username : NULL;
                $item->real_name      = $user ? $user->real_name : NULL;
                $item->head_img_url   = $user ? $user->head_img_url : NULL;
                $item->shop_name      = $activities->get()->isNotEmpty() && $activities->first()->shops()->get()->isNotEmpty() ?
                    $activities->first()->shops()->first()->shop_name : NULL;
                $item->write_offer_name = $writeOffers->get()->isNotEmpty() ? $writeOffers->first()->username : NULL;

                return $item;
            })
        );
    }
}
