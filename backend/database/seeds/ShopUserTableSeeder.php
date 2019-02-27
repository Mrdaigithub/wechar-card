<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopUserTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table("shop_user")->insert(["shop_id" => 1, "user_id" => 2]);
        DB::table("shop_user")->insert(["shop_id" => 1, "user_id" => 3]);
    }
}
