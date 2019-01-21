<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("user")->insert([
            "username" => "root",
            "openid" => "oWqQa6K2egw4ijKVOAC-tffxhxKf",
            "Identity" => 3,
            "created_at" => date('Y-m-d h:m:s', time()),
            "updated_at" => date('Y-m-d h:m:s', time()),
        ]);
        DB::table("user")->insert([
            "username" => "shop_boss_1",
            "Identity" => 1,
            "openid" => "ocYxcuBt0mRugKZ7tGAHPnUaOW7Y",
            "created_at" => date('Y-m-d h:m:s', time()),
            "updated_at" => date('Y-m-d h:m:s', time()),
        ]);
        DB::table("user")->insert([
            "username" => "shop_worker_1",
            "Identity" => 2,
            "openid" => "ocYxcuAEy30bX0NXmGn4ypqx3tI0",
            "created_at" => date('Y-m-d h:m:s', time()),
            "updated_at" => date('Y-m-d h:m:s', time()),
        ]);
    }
}
