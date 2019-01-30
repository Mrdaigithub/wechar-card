<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Model\Activity;
use App\Model\Card;
use App\Model\Shop;
use Tymon\JWTAuth\Facades\JWTAuth;

class CardController extends ApiController {
  
  private $oneself;
  
  /**
   * CardController constructor.
   */
  public function __construct() {
    $this->oneself = JWTAuth::parseToken()->authenticate();
  }
  
  /**
   * 获取卡券列表
   *
   * @return mixed
   */
  public function list() {
    return $this->success(Card::all());
  }
  
  /**
   * 获取与指定商铺关联的卡券
   *
   * @param $id
   *
   * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
   */
  public function getCardByShopId($id) {
    $activities = Shop::find($id)->activities();
    if ($activities->count() <= 0) {
      return $this->badRequest(NULL, "单前商铺未参加任何活动");
    }
    $card = $activities->first()->cards();
    if ($card->count() <= 0) {
      return $this->badRequest(NULL, "单前活动未选取任何卡券奖品");
    }
    $cardArray = $card->get()->toArray();
    if ($cardArray > 8) {
      array_splice($cardArray, 8, count($cardArray));
    }
    
    return $this->success($cardArray);
  }
  
  /**
   * 获取用户当前商铺中奖的奖品id
   *
   * @param $id
   *
   * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
   */
  public function getLotteryCardIdByShopId($id) {
    $activities = Shop::find($id)->activities();
    if ($activities->count() <= 0) {
      return $this->badRequest(NULL, "单前商铺未参加任何活动");
    }
    $card = $activities->first()->cards();
    if ($card->count() <= 0) {
      return $this->badRequest(NULL, "单前活动未选取任何卡券奖品");
    }
    if ($this->oneself["lottery_num"] <= 0) {
      return $this->badRequest(NULL, "没有抽奖次数");
    }
    $cardArray = $card->get()->toArray();
    if ($cardArray > 8) {
      array_splice($cardArray, 8, count($cardArray));
    }
    
    // 去除概率为0的奖项
    $cardArray = array_filter($cardArray,
      function($item) {
        if ($item["probability"] <= 0) {
          return FALSE;
        }
        return TRUE;
      });
    
    // 获取卡券的概率列表
    $cardProbabilityList = [];
    foreach ($cardArray as $item) {
      array_push($cardProbabilityList,
        [
          "id"          => $item["id"],
          "probability" => $item["probability"],
        ]);
    }
    
    // 随机抽取卡券
    $res = NULL;
    for (
      $randomNum = lcg_value(), $sum = 0, $item = 0;
      $item < count($cardProbabilityList); $item++
    ) {
      $sum += $cardProbabilityList[$item]["probability"];
      if ($randomNum <= $sum) {
        $res = $cardProbabilityList[$item]["id"];
        break;
      }
    }
    
    // 用户抽奖次数-1
    $this->oneself["lottery_num"] -= 1;
    $this->save_model($this->oneself);
    
    // 为用户创建抽到的卡券
    if ($res) {
      $cardModel               = Card::find($res);
      $newCard                 = new Card();
      $newCard->card_name      = $cardModel->card_name;
      $newCard->card_thumbnail = $cardModel->card_thumbnail;
      $newCard->end_time_0     = $cardModel->end_time_0;
      $newCard->end_time_1     = $cardModel->end_time_1;
      $newCard->probability    = $cardModel->probability;
      $newCard->state          = $cardModel->state;
      $newCard->type           = TRUE;
      $newCard->parentid       = $cardModel->id;
      $newCard->remarks        = $cardModel->remarks;
      $this->save_model($newCard);
      $newCard->user()->attach($this->oneself->id);
    }
    
    return $this->success($res);
  }
}
