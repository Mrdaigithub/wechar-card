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
      "card_name"   => "card_1",
      "end_time_0"  => date('Y-m-d h:m:s', time() + 3600 * 24 * 7),
      "probability" => "0.3",
      "state"       => TRUE,
      "type"        => FALSE,
      "parentid"    => NULL,
      "remarks"     => "remarks_1",
      "created_at"  => date('Y-m-d h:m:s', time()),
      "updated_at"  => date('Y-m-d h:m:s', time()),
    ]);
    DB::table("card")->insert([
      "card_name"   => "card_2",
      "end_time_0"  => date('Y-m-d h:m:s', time() + 3600 * 24 * 7),
      "probability" => "0.0",
      "state"       => TRUE,
      "type"        => FALSE,
      "parentid"    => NULL,
      "remarks"     => "remarks_2",
      "created_at"  => date('Y-m-d h:m:s', time()),
      "updated_at"  => date('Y-m-d h:m:s', time()),
    ]);
    DB::table("card")->insert([
      "card_name"   => "card_3",
      "end_time_1"  => 3600 * 24 * 30,
      "probability" => "0.1",
      "state"       => TRUE,
      "type"        => FALSE,
      "parentid"    => NULL,
      "remarks"     => "remarks_3",
      "created_at"  => date('Y-m-d h:m:s', time()),
      "updated_at"  => date('Y-m-d h:m:s', time()),
    ]);
    DB::table("card")->insert([
      "card_name"   => "card_4",
      "end_time_0"  => date('Y-m-d h:m:s', time() + 3600 * 24 * 7),
      "probability" => "0.2",
      "state"       => TRUE,
      "type"        => FALSE,
      "parentid"    => NULL,
      "remarks"     => "remarks_4",
      "created_at"  => date('Y-m-d h:m:s', time()),
      "updated_at"  => date('Y-m-d h:m:s', time()),
    ]);
    DB::table("card")->insert([
      "card_name"   => "card_5",
      "end_time_1"  => 3600 * 24 * 30,
      "probability" => "0.01",
      "state"       => TRUE,
      "type"        => FALSE,
      "parentid"    => NULL,
      "remarks"     => "remarks_5",
      "created_at"  => date('Y-m-d h:m:s', time()),
      "updated_at"  => date('Y-m-d h:m:s', time()),
    ]);
    DB::table("card")->insert([
      "card_name"   => "card_6",
      "end_time_0"  => date('Y-m-d h:m:s', time() + 3600 * 24 * 7),
      "probability" => "0.07",
      "state"       => TRUE,
      "type"        => FALSE,
      "parentid"    => NULL,
      "remarks"     => "remarks_6",
      "created_at"  => date('Y-m-d h:m:s', time()),
      "updated_at"  => date('Y-m-d h:m:s', time()),
    ]);
    DB::table("card")->insert([
      "card_name"   => "card_7",
      "end_time_0"  => date('Y-m-d h:m:s', time() + 3600 * 24 * 7),
      "probability" => "0.5",
      "state"       => TRUE,
      "type"        => FALSE,
      "parentid"    => NULL,
      "remarks"     => "remarks_7",
      "created_at"  => date('Y-m-d h:m:s', time()),
      "updated_at"  => date('Y-m-d h:m:s', time()),
    ]);
    DB::table("card")->insert([
      "card_name"   => "card_8",
      "end_time_1"  => 3600 * 24 * 30,
      "probability" => "0.3",
      "state"       => TRUE,
      "type"        => FALSE,
      "parentid"    => NULL,
      "remarks"     => "remarks_8",
      "created_at"  => date('Y-m-d h:m:s', time()),
      "updated_at"  => date('Y-m-d h:m:s', time()),
    ]);
    DB::table("card")->insert([
      "card_name"   => "card_9",
      "end_time_0"  => date('Y-m-d h:m:s', time() + 3600 * 24 * 7),
      "probability" => "0.2",
      "state"       => TRUE,
      "type"        => FALSE,
      "parentid"    => NULL,
      "remarks"     => "remarks_9",
      "created_at"  => date('Y-m-d h:m:s', time()),
      "updated_at"  => date('Y-m-d h:m:s', time()),
    ]);
    DB::table("card")->insert([
      "card_name"   => "card_10",
      "end_time_1"  => 3600 * 24 * 30,
      "probability" => "0.0",
      "state"       => TRUE,
      "type"        => FALSE,
      "parentid"    => NULL,
      "remarks"     => "remarks_10",
      "created_at"  => date('Y-m-d h:m:s', time()),
      "updated_at"  => date('Y-m-d h:m:s', time()),
    ]);
  }
}
