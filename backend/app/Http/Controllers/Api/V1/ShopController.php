<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Model\Activity;
use App\Model\Shop;

class ShopController extends ApiController {

  /**
   * 获取所有商铺列表
   *
   * @return mixed
   */
  public function list() {
    $shopList = Shop::all();
    foreach ($shopList as $item) {
      $shop_activity = $item->activity()->first();
      if ($shop_activity) {
        $item->activity_id = $shop_activity["id"];
      }
      else {
        $item->activity_id = NULL;
      }
    }
    return $this->success($shopList);
  }

  /**
   * 新建商铺
   *
   * @param \App\Http\Requests\StoreShopRequest $request
   *
   * @return mixed
   */
  public function store(StoreShopRequest $request) {
    if ($request->has("activity_id")
        && (Activity::find($request->get("activity_id"))->shops()->count()
            <= 0)) {
      return $this->badRequest(NULL, "此活动已被占用");
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

    $this->save_model($shop);
    if ($request->has("activity_id")) {
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

    if (!$shop) {
      return $this->notFound();
    }
    if ($request->has("activity_id")) {
      $activity = Activity::find($request->get("activity_id"));
      if (!$activity) {
        return $this->notFound();
      }
      $activity_shops = $activity->shops();
      if ($activity_shops->count() > 0 && $activity_shops->first()->id != $id) {
        return $this->badRequest(NULL, "此活动已被占用");
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

    $this->save_model($shop);

    if ($request->has("activity_id")) {
      $shop->activity()->detach();
      $shop->activity()->attach($request->get("activity_id"));
    }

    return Shop::find($shop->id);
  }

  /**
   * 删除商铺
   *
   * @param $id
   */
  public function remove($id) {
    // Todo 删除店铺关联
    Shop::find($id)->activity()->detach();
    Shop::destroy($id);
    return;
  }
}
