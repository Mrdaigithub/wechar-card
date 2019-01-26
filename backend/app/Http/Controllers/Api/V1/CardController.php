<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Model\Activity;
use App\Model\Card;
use App\Model\Shop;

class CardController extends ApiController {

  /**
   * 获取卡券列表
   *
   * @return mixed
   */
  public function list() {
    return $this->success(Card::all());
  }

  public function getCardByShopId($id) {
    $activities = Shop::find($id)->activities();
    if ($activities->count() <= 0) {
      return $this->badRequest(NULL, "单前商铺未参加任何活动");
    }
    $card = $activities->first()->cards();
    if ($card->count() <= 0) {
      return $this->badRequest(NULL, "单前活动未选取任何卡券奖品");
    }

    return $this->success($card->get());
  }
}
