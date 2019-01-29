<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSignInTableSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    DB::table("user_sign_in")->insert([
      "sign_in_id" => 1,
      "user_id"    => 4,
    ]);
  }
}
