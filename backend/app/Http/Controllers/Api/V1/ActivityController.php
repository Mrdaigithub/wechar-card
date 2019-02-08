<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Model\Activity;
use App\Model\Card;
use App\Model\Shop;
use App\Utils\ResponseMessage;
use Illuminate\Http\Request;

class ActivityController extends ApiController {

    /**
     * 获取所有活动列表
     *
     * @return mixed
     */
    public function list() {
        return $this->success(
            Activity::all()
                ->map(function ($item) {
                    $card_model_list = $item->cards();
                    $activityShops   = $item->shops();
                    $winningLogs     = $item->winningLogs();

                    $item->shop_id            = $activityShops->get()->isNotEmpty() ? $activityShops->first()->id : NULL;
                    $item->card_model_id_list = $card_model_list->get()->isNotEmpty() ?
                        $card_model_list->get()->map(function ($item) {
                            return $item->id;
                        }) : NULL;
                    $item->customer_num       = $winningLogs->get()->isNotEmpty() ?
                        $winningLogs->get()->map(function ($item) {
                            return $item->user()->first()->id;
                        })->unique()->values()->count() : 0;

                    return $item;
                })
        );
    }

    /**
     * 获取与指定商铺关联的活动
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function getActivityByShopId($id) {
        $activities = Shop::find($id)->activity();
        if ($activities->count() <= 0) {
            return $this->badRequest(NULL, ResponseMessage::$message[400012]);
        }

        return $this->success($activities->first());
    }

    /**
     * 新建活动
     *
     * @param \App\Http\Requests\StoreActivityRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function store(StoreActivityRequest $request) {
        if ($request->has("reply_keyword")
            && Activity::where("reply_keyword", $request->get("reply_keyword"))->get()->isNotEmpty()) {
            return $this->badRequest(NULL, ResponseMessage::$message[400019]);
        }

        $activity                = new Activity();
        $activity->activity_name = $request->get("activity_name");
        if ($request->has("activity_description")) {
            $activity->activity_description = $request->get("activity_description");
        }
        if ($request->has("activity_thumbnail")) {
            $activity->activity_thumbnail = $request->get("activity_thumbnail");
        }
        if ($request->has("reply_keyword")) {
            $activity->reply_keyword = $request->get("reply_keyword");
        }
        if ($request->has("remarks")) {
            $activity->remarks = $request->get("remarks");
        }
        if ($request->has("state")) {
            $activity->state = $request->get("state");
        }

        $this->save_model($activity);

        if ($request->has("card_model_id_list")) {
            $activity->cards()->attach($request->get("card_model_id_list"));
        }

        return $this->success(Activity::find($activity->id));
    }

    /**
     * 更新活动
     *
     * @param \App\Http\Requests\UpdateActivityRequest $request
     * @param                                          $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(UpdateActivityRequest $request, $id) {
        $activity = Activity::find($id);

        if ( ! $activity) {
            return $this->notFound();
        }
        // 排除关联不存在的卡券id
        if ($request->has("card_model_id_list") && $request->get("card_model_id_list")) {
            foreach ($request->get("card_model_id_list") as $item) {
                if ( ! Card::find($item)) {
                    return $this->notFound();
                }
            }
        }

        $activity->activity_name = $request->get("activity_name");
        if ($request->has("activity_description")) {
            $activity->activity_description = $request->get("activity_description") ? $request->get("activity_description") : "";
        }
        if ($request->has("activity_thumbnail")) {
            $activity->activity_thumbnail = $request->get("activity_thumbnail");
        }
        if ($request->has("reply_keyword")) {
            $activityReplyKeyword = Activity::where("reply_keyword", $request->get("reply_keyword"));
            if ($activityReplyKeyword->get()->isNotEmpty() && $activityReplyKeyword->first()->id != $id) {
                return $this->badRequest(NULL, ResponseMessage::$message[400019]);
            }
        }
        if ($request->has("state")) {
            $activity->state = $request->get("state");
        }
        if ($request->has("customer_num")) {
            $activity->customer_num = $request->get("customer_num") ? $request->get("customer_num") : 0;
        }
        if ($request->has("remarks")) {
            $activity->remarks = $request->get("remarks") ? $request->get("remarks") : "";
        }
        if ($request->has("card_model_id_list")) {
            $request->card_model_id_list = $request->get("card_model_id_list") ? $request->get("card_model_id_list") : [];
        }

        $this->save_model($activity);

        if ($request->has("card_model_id_list")) {
            $activity->cards()->detach();
            $activity->cards()->attach($request->get("card_model_id_list"));
        }

        return $activity;
    }

    /**
     * 删除活动
     *
     * @param $id
     */
    public function remove($id) {
        $activity = Activity::find($id);
        $activity->shops()->detach();
        $activity->cards()->detach();
        Activity::destroy($id);
    }
}
