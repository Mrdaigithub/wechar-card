<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SignInTableSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    $log = "";
    for ($i = 11; $i >= 0; $i--) {
      $log .= "," . date('Y-m-d', strtotime("-$i day"));
    };
    DB::table("sign_in")->insert([
      "month_sign_in_log" => trim($log, ","),
      "created_at"        => date('Y-m-d h:i:s', time()),
      "updated_at"        => date('Y-m-d h:i:s', time()),
    ]);
  }
}
