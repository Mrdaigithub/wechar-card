<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("system_config")->insert([
            "config_name" => "抽奖填写信息",
            "config_value" => "true",
            "created_at" => date('Y-m-d h:m:s', time()),
            "updated_at" => date('Y-m-d h:m:s', time()),
        ]);
        DB::table("system_config")->insert([
            "config_name" => "关注公众号抽奖",
            "config_value" => "true",
            "created_at" => date('Y-m-d h:m:s', time()),
            "updated_at" => date('Y-m-d h:m:s', time()),
        ]);
    }
}
