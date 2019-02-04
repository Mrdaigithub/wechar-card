<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Model\Activity;
use App\Model\Shop;

class ActivityController extends ApiController {

	/**
	 * 获取卡券列表
	 *
	 * @return mixed
	 */
	public function list() {
		$activityList = Activity::all();

		foreach ( $activityList as $item ) {
			$activity_shop = $item->shops()->first();
			if ( $activity_shop ) {
				$item->shop_id = $activity_shop["id"];
			} else {
				$item->shop_id = NULL;
			}
		}

		return $this->success( $activityList );
	}

	/**
	 * 获取与指定商铺关联的活动
	 *
	 * @param $id
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
	 */
	public function getActivityByShopId( $id ) {
		$activities = Shop::find( $id )->activity();
		if ( $activities->count() <= 0 ) {
			return $this->badRequest( NULL, "单前商铺未参加任何活动" );
		}

		return $this->success( $activities->first() );
	}
}
