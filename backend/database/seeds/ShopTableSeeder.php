<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		DB::table("shop")->insert([
			"shop_name"     => "商铺1",
			"shop_location" => "商铺地址1",
			"started_at"    => date('Y-m-d h:m:s', time()),
			"state"         => true,
			"remarks"       => "商铺备注1",
			"created_at"    => date('Y-m-d h:m:s', time()),
			"updated_at"    => date('Y-m-d h:m:s', time()),
		]);
		DB::table("shop")->insert([
			"shop_name"     => "商铺2",
			"shop_location" => "商铺地址2",
			"started_at"    => date('Y-m-d h:m:s', time()),
			"state"         => false,
			"remarks"       => "商铺备注2",
			"created_at"    => date('Y-m-d h:m:s', time()),
			"updated_at"    => date('Y-m-d h:m:s', time()),
		]);
		DB::table("shop")->insert([
			"shop_name"     => "商铺3",
			"shop_location" => "商铺地址3",
			"started_at"    => date('Y-m-d h:m:s', time()),
			"state"         => true,
			"remarks"       => "商铺备注3",
			"created_at"    => date('Y-m-d h:m:s', time()),
			"updated_at"    => date('Y-m-d h:m:s', time()),
		]);
	}
}
