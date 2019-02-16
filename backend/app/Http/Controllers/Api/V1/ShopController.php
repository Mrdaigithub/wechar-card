<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Model\Activity;
use App\Model\Shop;
use App\Utils\ResponseMessage;
use Tymon\JWTAuth\Facades\JWTAuth;

class ShopController extends ApiController {
    /**
     * 获取所有商铺列表
     *
     * @return mixed
     */
    public function list() {
        $shopList = Shop::all()->map(function ($item) {
            $shopActivity = $item->activity();
            if ($shopActivity->get()->isEmpty()) {
                $item->activity_id = NULL;
            } else {
                $item->activity_id = $shopActivity->first()->id;
            }

            return $item;
        });

        return $this->success($shopList);
    }

    /**
     * 获取老板的商铺
     *
     * @return mixed
     */
    public function getShopByBoss() {
        $shops = JWTAuth::parseToken()->authenticate()->shop();
        if ($shops->get()->isEmpty()) {
            return $this->badRequest(NULL, ResponseMessage::$message[400028]);
        }

        return $this->success($shops->first());
    }

    /**
     * 获取老板的商铺
     *
     * @return mixed
     */
    public function getShopById($id) {
        $shop = Shop::find($id);

        if ( ! $shop) {
            return $this->badRequest(NULL, ResponseMessage::$message[400028]);
        }

        return $this->success($shop);
    }

    /**
     * 新建商铺
     *
     * @param \App\Http\Requests\StoreShopRequest $request
     *
     * @return mixed
     */
    public function store(StoreShopRequest $request) {
        if ($request->has("activity_id") &&
            $request->get("activity_id") &&
            (Activity::find($request->get("activity_id"))->shops()->get()->isEmpty())) {
            return $this->badRequest(NULL, ResponseMessage::$message[400011]);
        }

        $shop                = new Shop();
        $shop->shop_name     = $request->get("shop_name");
        $shop->shop_location = $request->get("shop_location");
        if ($request->has("started_at")) {
            $shop->started_at = date("Y-m-d h:i:s",
                strtotime($request->get("started_at")));
        }
        if ($request->has("remarks")) {
            $shop->remarks = $request->get("remarks");
        }
        if ($request->has("state")) {
            $shop->state = $request->get("state");
        }

        $this->saveModel($shop);
        if ($request->has("activity_id") && $request->get("activity_id")) {
            $shop->activity()->attach($request->get("activity_id"));
        }

        return $this->success(Shop::find($shop->id));
    }

    /**
     * 更新商铺
     *
     * @param \App\Http\Requests\UpdateShopRequest $request
     * @param                                      $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(UpdateShopRequest $request, $id) {
        $shop = Shop::find($id);

        if ( ! $shop) {
            return $this->notFound();
        }
        if ($request->has("activity_id") && ! ! $request->get("activity_id")) {
            $activity = Activity::find($request->get("activity_id"));
            if ( ! $activity) {
                return $this->notFound();
            }
            $activity_shops = $activity->shops();
            if ($activity_shops->count() > 0 && $activity_shops->first()->id != $id) {
                return $this->badRequest(NULL, ResponseMessage::$message[400011]);
            }
        }

        if ($request->has("shop_name")) {
            $shop->shop_name = $request->get("shop_name");
        }
        if ($request->has("shop_location")) {
            $shop->shop_location = $request->get("shop_location");
        }
        if ($request->has("started_at")) {
            $shop->started_at = date("Y-m-d h:i:s",
                strtotime($request->get("started_at")));
        }
        if ($request->has("remarks")) {
            $shop->remarks = $request->get("remarks");
        }
        if ($request->has("state")) {
            $shop->state = $request->get("state");
        }

        $this->saveModel($shop);

        if ($request->has("activity_id")) {
            $shop->activity()->detach();
            $shop->activity()->attach($request->get("activity_id"));
        } elseif ($request->has("activity_id") && ! $request->get("activity_id")) {
            $shop->activity()->detach();
        }

        return Shop::find($shop->id);
    }

    /**
     * 删除商铺
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|void
     */
    public function remove($id) {
        Shop::find($id)->activity()->detach();
        Shop::destroy($id);

        return;
    }
}
