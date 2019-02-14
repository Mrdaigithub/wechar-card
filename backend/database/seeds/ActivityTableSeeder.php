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
            "activity_thumbnail"   => "https://www.baidu.com/img/xinshouye_f097208390e839e5b5295804227d94e9.png",
            "state"                => TRUE,
            "remarks"              => "备注1",
            "reply_keyword"        => "回复关键词1",
            "created_at"           => date('Y-m-d h:i:s', time()),
            "updated_at"           => date('Y-m-d h:i:s', time()),
        ]);
        DB::table("activity")->insert([
            "activity_name"        => "活动名2",
            "activity_description" => "活动详情2",
            "activity_thumbnail"   => "https://www.baidu.com/img/xinshouye_f097208390e839e5b5295804227d94e9.png",
            "state"                => TRUE,
            "remarks"              => "备注2",
            "reply_keyword"        => "回复关键词2",
            "created_at"           => date('Y-m-d h:i:s', time()),
            "updated_at"           => date('Y-m-d h:i:s', time()),
        ]);
    }
}
