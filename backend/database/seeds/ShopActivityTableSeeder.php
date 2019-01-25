<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopActivityTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		DB::table("shop_activity")->insert(["shop_id" => 1, "activity_id" => 1]);
		DB::table("shop_activity")->insert(["shop_id" => 1, "activity_id" => 2]);
	}
}
