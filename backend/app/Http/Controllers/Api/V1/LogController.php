<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\UpdateSystemConfigRequest;
use App\Model\SystemConfig;
use App\Model\WinningLog;
use function PHPSTORM_META\map;

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
}
