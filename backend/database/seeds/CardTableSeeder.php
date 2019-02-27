<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardTableSeeder extends Seeder {
  
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    DB::table("card")->insert([
      "card_name"   => "一等奖",
      "end_time_0"  => date('Y-m-d h:i:s', time() + 3600 * 24 * 7),
      "probability" => "0.2",
      "state"       => TRUE,
      "type"        => FALSE,
      "parentid"    => NULL,
      "remarks"     => "海底捞1元卷",
      "created_at"  => date('Y-m-d h:i:s', time()),
      "updated_at"  => date('Y-m-d h:i:s', time()),
    ]);
    DB::table("card")->insert([
      "card_name"   => "二等奖",
      "end_time_1"  => 1800,
      "probability" => "0.2",
      "state"       => TRUE,
      "type"        => FALSE,
      "parentid"    => NULL,
      "remarks"     => "海底捞2元卷",
      "created_at"  => date('Y-m-d h:i:s', time()),
      "updated_at"  => date('Y-m-d h:i:s', time()),
    ]);
    DB::table("card")->insert([
      "card_name"   => "三等奖",
      "end_time_1"  => 1800,
      "probability" => "0.1",
      "state"       => TRUE,
      "type"        => FALSE,
      "parentid"    => NULL,
      "remarks"     => "海底捞3元卷",
      "created_at"  => date('Y-m-d h:i:s', time()),
      "updated_at"  => date('Y-m-d h:i:s', time()),
    ]);
    DB::table("card")->insert([
      "card_name"   => "四等奖",
      "end_time_1"  => 1800,
      "probability" => "0.2",
      "state"       => TRUE,
      "type"        => FALSE,
      "parentid"    => NULL,
      "remarks"     => "海底捞4元卷",
      "created_at"  => date('Y-m-d h:i:s', time()),
      "updated_at"  => date('Y-m-d h:i:s', time()),
    ]);
    DB::table("card")->insert([
      "card_name"   => "五等奖",
      "end_time_1"  => 1800,
      "probability" => "0.2",
      "state"       => TRUE,
      "type"        => FALSE,
      "parentid"    => NULL,
      "remarks"     => "海底捞5元卷",
      "created_at"  => date('Y-m-d h:i:s', time()),
      "updated_at"  => date('Y-m-d h:i:s', time()),
    ]);
  }
}
