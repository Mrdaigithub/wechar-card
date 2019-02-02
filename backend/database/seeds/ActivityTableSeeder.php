<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		DB::table("activity")->insert([
			"activity_name"        => "活动名1",
			"activity_description" => "活动详情1",
			"activity_thumbnail"   => "http://mmbiz.qpic.cn/mmbiz_jpg/c5vy0YJlTVVy7sfKKe8EQShvKEYaKPnS2wfphdib01B105eVCbMz2Foqficsz2lVacm8dFoukpCcbWyG2iah7vuLA/0?wx_fmt=jpeg",
			"start_time"           => date('Y-m-d h:i:s', time()),
			"end_time"             => date('Y-m-d h:i:s', time() + 3600 * 24 * 7),
			"state"                => true,
			"customer_num"         => "30",
			"remarks"              => "备注1",
			"reply_keyword"        => "回复关键词1",
			"created_at"           => date('Y-m-d h:i:s', time()),
			"updated_at"           => date('Y-m-d h:i:s', time()),
		]);
		DB::table("activity")->insert([
			"activity_name"        => "活动名2",
			"activity_description" => "活动详情2",
			"activity_thumbnail"   => "http://120.fc120.org/templets/bb/images640/banner1.jpg",
			"start_time"           => date('Y-m-d h:i:s', time()),
			"end_time"             => date('Y-m-d h:i:s', time() + 3600 * 24 * 7),
			"state"                => true,
			"customer_num"         => "60",
			"remarks"              => "备注2",
			"reply_keyword"        => "回复关键词2",
			"created_at"           => date('Y-m-d h:i:s', time()),
			"updated_at"           => date('Y-m-d h:i:s', time()),
		]);
	}
}
