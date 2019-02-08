<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\GetLotteryCardByShopIdRequest;
use App\Http\Requests\StoreCardModelRequest;
use App\Http\Requests\UpdateCardModelRequest;
use App\Model\Card;
use App\Model\Shop;
use App\Model\WinningLog;
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
     * 获取卡券模版列表
     *
     * @return mixed
     */
    public function list() {
        $cardList = Card::where("type", 0)
            ->get()
            ->map(function ($item) {
                $cardActivity = $item->activity();
                if ($cardActivity->get()->isEmpty()) {
                    $item->activity_id_list = NULL;
                } else {
                    $item->activity_id_list = $cardActivity->get()
                        ->map(function ($item) {
                            return $item->id;
                        });
                }

                return $item;
            });

        return $this->success($cardList);
    }

    /**
     * 获取与指定商铺关联的卡券模板
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function getCardByShopId($id) {
        $activities = Shop::find($id)->activity();
        if ($activities->count() <= 0) {
            return $this->badRequest(NULL, "单前商铺未参加任何活动");
        }
        $cardModelList = $activities->first()->cards();

        // 去除被禁用,类型不为卡券模板,已过期的卡券模板
        $cardModelList = array_filter($cardModelList->get()->toArray(),
            function ($item) {
                if (($item["state"] == 1)
                    && ($item["type"] == 0)
                    && ($item["end_time_0"])
                    && (strtotime($item["end_time_0"]) > strtotime(date('Y-m-d h:i:s',
                            time())))) {
                    return TRUE;
                } elseif (($item["state"] == 1)
                    && ($item["type"] == 0)
                    && ($item["end_time_1"])) {
                    return TRUE;
                }

                return FALSE;
            });
        if (count($cardModelList) <= 0) {
            return $this->badRequest(NULL, "单前活动未选取任何卡券奖品");
        }
        $cardArray = $cardModelList;
        if ($cardArray > 8) {
            array_splice($cardArray, 8, count($cardArray));
        }

        return $this->success($cardArray);
    }

    /**
     * 获取与用户在当前商铺中过的卡券
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function getUserCardByShopId($id) {
        $cardList = array_map(function ($item) {
            // 时间1过期卡券失效
            if ( ! ! $item["end_time_0"]) {
                $item["state"] = strtotime($item["end_time_0"])
                > strtotime(date('Y-m-d h:i:s', time())) ? 1 : 0;
            } // 时间2过期卡券失效
            elseif ( ! ! $item["end_time_1"]) {
                $item["state"] = strtotime(date('Y-m-d H:i:s',
                    strtotime("+" . $item["end_time_1"] . " seconds",
                        date(strtotime($item["created_at"])))))
                > strtotime(date('Y-m-d h:i:s', time())) ? 1 : 0;
            }
            // 如果卡券模板被禁用下级卡券全部失效(优先级最高,要放最下面)
            if ( ! Card::find($item["parentid"])["state"]) {
                $item["state"] = 0;
            }

            return $item;
        }, $this->oneself->cardList()->get()->toArray());

        return $this->success($cardList);
    }

    /**
     * 获取用户当前商铺中奖的奖品id
     *
     * @param                                                  $id
     *
     * @param \App\Http\Requests\GetLotteryCardByShopIdRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function getLotteryCardIdByShopId($id, GetLotteryCardByShopIdRequest $request) {
        $location   = $request->get("location");
        $activities = Shop::find($id)->activity();

        if ($activities->get()->isEmpty()) {
            return $this->badRequest(NULL, "单前商铺未参加任何活动");
        }
        if ($activities->first()->shops()->get()->isEmpty()) {
            return $this->badRequest(NULL, "未有商家参加当前活动");
        }

        // 判断用户单前地区
        if ($activities->first()->shops()->first()->shop_location != $location) {
            return $this->badRequest(NULL, "当前所在区域无法参加活动");
        }

        $cardModelList = $activities->first()->cards();

        // 去除被禁用,类型不为卡券模板,已过期的卡券模板
        $cardModelList = $cardModelList->get()->filter(function ($item) {
            if (($item["state"] == 1)
                && ($item["type"] == 0)
                && ($item["end_time_0"])
                && (strtotime($item["end_time_0"]) > strtotime(date('Y-m-d h:i:s',
                        time())))) {
                return TRUE;
            } elseif (($item["state"] == 1)
                && ($item["type"] == 0)
                && ($item["end_time_1"])) {
                return TRUE;
            }

            return FALSE;
        });

        if (count($cardModelList) <= 0) {
            return $this->badRequest(NULL, "单前活动未选取任何卡券奖品");
        }
        if ($this->oneself["lottery_num"] <= 0) {
            return $this->badRequest(NULL, "没有抽奖次数");
        }
        if ($cardModelList->count() > 8) {
            $cardModelList->splice(8, $cardModelList->count());
        }

        // 去除概率为0的奖项,获取卡券的概率列表
        $cardProbabilityList = $cardModelList->filter(function ($item) {
            if ($item["probability"] <= 0) {
                return FALSE;
            }

            return TRUE;
        })->values();

        // 随机抽取卡券
        $res     = NULL;
        $newCard = NULL;
        for ($randomNum = lcg_value(), $sum = 0, $item = 0; $item < count($cardProbabilityList); $item++) {
            $sum += $cardProbabilityList[ $item ]["probability"];
            if ($randomNum <= $sum) {
                $res = $cardProbabilityList[ $item ]["id"];
                break;
            }
        }

        if ($res) {
            // 为用户创建抽到的卡券
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

            // 添加中奖记录
            $winningLog           = new WinningLog();
            $winningLog->location = $location;

            $this->save_model($winningLog);

            $winningLog->user()->attach($this->oneself["id"]);
            $winningLog->card()->attach($newCard->id);
            $winningLog->activity()->attach($activities->first()->id);
        }

        // 用户抽奖次数-1
        $this->oneself["lottery_num"] -= 1;
        $this->save_model($this->oneself);

        return $this->success([
            "index"   => $res,
            "card_id" => $newCard ? $newCard->id : NULL,
        ]);
    }

    /**
     * 新建卡券模版
     *
     * @param \App\Http\Requests\StoreCardModelRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function storeCardModel(StoreCardModelRequest $request) {
        if ( ! $request->has("end_time_0") && ! $request->has("end_time_1")) {
            return $this->badRequest(NULL, "卡券结束时间参数缺失");
        }

        $cardModel = new Card();

        $cardModel->card_name = $request->get("card_name");
        if ($request->has("card_thumbnail")) {
            $cardModel->card_thumbnail = $request->get("card_thumbnail");
        }
        if ($request->has("end_time_0")) {
            $cardModel->end_time_0 = date("Y-m-d h:i:s",
                strtotime($request->get("end_time_0")));
        } elseif ($request->has("end_time_1")) {
            $cardModel->end_time_1 = $request->get("end_time_1");
        }
        if ($request->has("probability")) {
            $cardModel->probability = $request->get("probability");
        }
        if ($request->has("remarks")) {
            $cardModel->remarks = $request->get("remarks");
        }
        if ($request->has("state")) {
            $cardModel->state = $request->get("state");
        }
        if ($request->has("type")) {
            $cardModel->type = $request->get("type");
        }

        $this->save_model($cardModel);

        return Card::find($cardModel->id);
    }

    /**
     * 更新卡券模版
     *
     * @param                                           $id
     * @param \App\Http\Requests\UpdateCardModelRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function updateCardModel(UpdateCardModelRequest $request, $id) {
        $cardModel = Card::find($id);

        if ( ! $cardModel) {
            return $this->notFound();
        }

        if ($request->has("card_name")) {
            $cardModel->card_name = $request->get("card_name");
        }
        if ($request->has("card_thumbnail")) {
            $cardModel->card_thumbnail = $request->get("card_thumbnail");
        }
        if ($request->has("end_time_0")) {
            $cardModel->end_time_0 = date("Y-m-d h:i:s",
                strtotime($request->get("end_time_0")));
            $cardModel->end_time_1 = NULL;
        } elseif ($request->has("end_time_1")) {
            $cardModel->end_time_1 = $request->get("end_time_1");
            $cardModel->end_time_0 = NULL;
        }
        if ($request->has("probability")) {
            $cardModel->probability = $request->get("probability");
        }
        if ($request->has("remarks")) {
            $cardModel->remarks = $request->get("remarks");
        }
        if ($request->has("state")) {
            $cardModel->state = $request->get("state");
        }
        if ($request->has("type")) {
            $cardModel->type = $request->get("type");
        }

        $this->save_model($cardModel);

        return Card::find($cardModel->id);
    }

    /**
     * 删除卡券模版
     *
     * @param $id
     *
     * @return mixed
     */
    public function removeCardModel($id) {
        Card::find($id)->activity()->detach();
        Card::destroy($id);

        return;
    }
}
