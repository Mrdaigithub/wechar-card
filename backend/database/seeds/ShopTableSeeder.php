<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("shop")->insert([
            "shop_name" => "商铺名商铺名商铺名商铺名1",
            "location" => "地址地址地址地址地址",
            "started_at" => date('Y-m-d h:m:s', time()),
            "state" => true,
            "remarks" => "备注备注备注备注备注",
            "created_at" => date('Y-m-d h:m:s', time()),
            "updated_at" => date('Y-m-d h:m:s', time()),
        ]);
        DB::table("shop")->insert([
            "shop_name" => "商铺名商铺名商铺名商铺名2",
            "location" => "地址地址地址地址地址",
            "started_at" => date('Y-m-d h:m:s', time()),
            "state" => false,
            "remarks" => "备注备注备注备注备注",
            "created_at" => date('Y-m-d h:m:s', time()),
            "updated_at" => date('Y-m-d h:m:s', time()),
        ]);
        DB::table("shop")->insert([
            "shop_name" => "123",
            "location" => "地址地址地址地址地址",
            "started_at" => date('Y-m-d h:m:s', time()),
            "state" => true,
            "remarks" => "备注备注备注备注备注",
            "created_at" => date('Y-m-d h:m:s', time()),
            "updated_at" => date('Y-m-d h:m:s', time()),
        ]);
    }
}
