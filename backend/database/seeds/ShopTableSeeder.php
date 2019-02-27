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
      "shop_name"     => "海底捞",
      "shop_location" => "温州市",
      "started_at"    => date('Y-m-d h:i:s', time()),
      "state"         => TRUE,
      "remarks"       => "海底捞备注",
      "created_at"    => date('Y-m-d h:i:s', time()),
      "updated_at"    => date('Y-m-d h:i:s', time()),
    ]);
  }
}
