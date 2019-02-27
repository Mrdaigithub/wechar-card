<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardActivityTableSeeder extends Seeder {
  
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    DB::table("card_activity")->insert(["card_id" => 1, "activity_id" => 1]);
    DB::table("card_activity")->insert(["card_id" => 2, "activity_id" => 1]);
    DB::table("card_activity")->insert(["card_id" => 3, "activity_id" => 1]);
    DB::table("card_activity")->insert(["card_id" => 4, "activity_id" => 1]);
    DB::table("card_activity")->insert(["card_id" => 5, "activity_id" => 1]);
  }
}
