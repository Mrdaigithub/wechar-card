<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("card")->insert([
            "card_name" => "card_1",
            "end_time" => date('Y-m-d h:m:s', time() + 3600 * 24 * 7),
            "state" => true,
            "probability" => "0.5",
            "all_num" => 100,
            "usable_num" => 99,
            "used_num" => 30,
            "remarks" => "remarks_1",
            "created_at" => date('Y-m-d h:m:s', time()),
            "updated_at" => date('Y-m-d h:m:s', time()),
        ]);
        DB::table("card")->insert([
            "card_name" => "card_2",
            "end_time" => date('Y-m-d h:m:s', time() + 3600 * 24 * 20),
            "state" => true,
            "probability" => "0.3",
            "all_num" => 200,
            "usable_num" => 200,
            "used_num" => 10,
            "remarks" => "remarks_2",
            "created_at" => date('Y-m-d h:m:s', time()),
            "updated_at" => date('Y-m-d h:m:s', time()),
        ]);
        DB::table("card")->insert([
            "card_name" => "card_3",
            "end_time" => date('Y-m-d h:m:s', time() + 3600 * 24 * 20),
            "state" => true,
            "probability" => "0.1",
            "all_num" => 200,
            "usable_num" => 100,
            "used_num" => 10,
            "remarks" => "remarks_3",
            "created_at" => date('Y-m-d h:m:s', time()),
            "updated_at" => date('Y-m-d h:m:s', time()),
        ]);
        DB::table("card")->insert([
            "card_name" => "card_4",
            "end_time" => date('Y-m-d h:m:s', time() + 3600 * 24 * 20),
            "state" => true,
            "probability" => "0.01",
            "all_num" => 10,
            "usable_num" => 5,
            "used_num" => 1,
            "remarks" => "remarks_4",
            "created_at" => date('Y-m-d h:m:s', time()),
            "updated_at" => date('Y-m-d h:m:s', time()),
        ]);
        DB::table("card")->insert([
            "card_name" => "card_5",
            "end_time" => date('Y-m-d h:m:s', time() + 3600 * 24 * 20),
            "state" => false,
            "probability" => "0.05",
            "all_num" => 30,
            "usable_num" => 20,
            "used_num" => 10,
            "remarks" => "remarks_5",
            "created_at" => date('Y-m-d h:m:s', time()),
            "updated_at" => date('Y-m-d h:m:s', time()),
        ]);
    }
}
