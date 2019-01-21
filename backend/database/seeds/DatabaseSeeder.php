<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(ShopTableSeeder::class);
        $this->call(CardTableSeeder::class);
        $this->call(ActivityTableSeeder::class);
        $this->call(SystemConfigTableSeeder::class);
    }
}
