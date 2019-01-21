<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("activity")->insert([
            "activity_name" => "activity_1",
            "description" => "activity_des_1",
            "img" => null,
            "start_time" => date('Y-m-d h:m:s', time()),
            "end_time" => date('Y-m-d h:m:s', time() + 3600 * 24 * 7),
            "state" => true,
            "customer_num" => "30",
            "remarks" => "remarks_1",
            "created_at" => date('Y-m-d h:m:s', time()),
            "updated_at" => date('Y-m-d h:m:s', time()),
        ]);
    }
}
