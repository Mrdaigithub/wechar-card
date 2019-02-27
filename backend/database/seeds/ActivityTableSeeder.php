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
            "activity_name"        => "海底捞免单活动",
            "activity_description" => "海底捞免单活动",
            "state"                => TRUE,
            "remarks"              => "备注1",
            "reply_keyword"        => "海底捞免单",
            "created_at"           => date('Y-m-d h:i:s', time()),
            "updated_at"           => date('Y-m-d h:i:s', time()),
        ]);
    }
}
