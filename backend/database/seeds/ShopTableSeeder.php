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
      "shop_location" => "温州市",
      "started_at"    => date('Y-m-d h:i:s', time()),
      "state"         => TRUE,
      "remarks"       => "商铺备注1",
      "created_at"    => date('Y-m-d h:i:s', time()),
      "updated_at"    => date('Y-m-d h:i:s', time()),
    ]);
    DB::table("shop")->insert([
      "shop_name"     => "商铺2",
      "shop_location" => "北京市",
      "started_at"    => date('Y-m-d h:i:s', time()),
      "state"         => FALSE,
      "remarks"       => "商铺备注2",
      "created_at"    => date('Y-m-d h:i:s', time()),
      "updated_at"    => date('Y-m-d h:i:s', time()),
    ]);
    DB::table("shop")->insert([
      "shop_name"     => "商铺3",
      "shop_location" => "温州市",
      "started_at"    => date('Y-m-d h:i:s', time()),
      "state"         => TRUE,
      "remarks"       => "商铺备注3",
      "created_at"    => date('Y-m-d h:i:s', time()),
      "updated_at"    => date('Y-m-d h:i:s', time()),
    ]);
  }
}
