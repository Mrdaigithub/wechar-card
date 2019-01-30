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
    DB::table("sign_in_user")->insert([
      "sign_in_id" => 1,
      "user_id"    => 4,
    ]);
  }
}
