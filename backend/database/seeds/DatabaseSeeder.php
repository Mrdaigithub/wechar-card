<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
  
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run() {
    $this->call(ActivityTableSeeder::class);
    $this->call(CardActivityTableSeeder::class);
    $this->call(CardTableSeeder::class);
    $this->call(CardUserTableSeeder::class);
    $this->call(ShopActivityTableSeeder::class);
    $this->call(ShopTableSeeder::class);
    $this->call(ShopUserTableSeeder::class);
    $this->call(SignInTableSeeder::class);
    $this->call(SignInUserTableSeeder::class);
    $this->call(SystemConfigTableSeeder::class);
    $this->call(UserSignInTableSeeder::class);
    $this->call(UserTableSeeder::class);
  }
}
