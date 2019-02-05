<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table("user")->insert([
            "username"   => "root",
            "openid"     => "oWqQa6K2egw4ijKVOAC-tffxhxKf",
            "Identity"   => 3,
            "remarks"    => "root_remarks",
            "created_at" => date('Y-m-d h:i:s', time()),
            "updated_at" => date('Y-m-d h:i:s', time()),
        ]);
        DB::table("user")->insert([
            "username"   => "shop_boss_1",
            "openid"     => "ocYxcuBt0mRugKZ7tGAHPnUaOW7Y",
            "Identity"   => 1,
            "remarks"    => "shop_boss_1_remarks",
            "created_at" => date('Y-m-d h:i:s', time()),
            "updated_at" => date('Y-m-d h:i:s', time()),
        ]);
        DB::table("user")->insert([
            "username"   => "shop_worker_1",
            "openid"     => "ocYxcuAEy30bX0NXmGn4ypqx3tI0",
            "Identity"   => 2,
            "remarks"    => "shop_worker_1_remarks",
            "created_at" => date('Y-m-d h:i:s', time()),
            "updated_at" => date('Y-m-d h:i:s', time()),
        ]);
        DB::table("user")->insert([
            "username"   => "shop_boss_2",
            "openid"     => "ocYxcuBt0mRugKZ7tGAHPnUaOW7a",
            "Identity"   => 1,
            "remarks"    => "shop_boss_2_remarks",
            "created_at" => date('Y-m-d h:i:s', time()),
            "updated_at" => date('Y-m-d h:i:s', time()),
        ]);
        DB::table("user")->insert([
            "username"   => "shop_worker_2",
            "openid"     => "ocYxcuBt0mRugKZ7tGAHPnUaOW7w",
            "Identity"   => 2,
            "remarks"    => "shop_worker_2_remarks",
            "created_at" => date('Y-m-d h:i:s', time()),
            "updated_at" => date('Y-m-d h:i:s', time()),
        ]);
        DB::table("user")->insert([
            "username"   => "user1",
            "openid"     => "ocYxcuAEy30bX0NXmGn4ypqx3tqq",
            "Identity"   => 0,
            "remarks"    => "user1_remarks",
            "created_at" => date('Y-m-d h:i:s', time()),
            "updated_at" => date('Y-m-d h:i:s', time()),
        ]);
    }
}
