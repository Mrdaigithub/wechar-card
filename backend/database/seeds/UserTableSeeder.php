<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run () {
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
        DB::table("user")->insert([
            "username"     => "亮",
            "head_img_url" => "http://thirdwx.qlogo.cn/mmopen/PiajxSqBRaEIgje8a8iaqO7GIzSAPwXg5uEISQbBT0TXBRCqyaBO9JHrehl9lPgErDicibB2gLicFXJib05vAbs8nU0w/132",
            "openid"       => "oWqQa6FfShBlltzOg3QQ8SXzOYRk",
            "Identity"     => 0,
            "remarks"      => "",
            "created_at"   => date('Y-m-d h:i:s', time()),
            "updated_at"   => date('Y-m-d h:i:s', time()),
        ]);
        DB::table("user")->insert([
            "username"     => "墨渊上神",
            "real_name"    => "苏军华",
            "phone"        => "17689975996",
            "head_img_url" => "http://thirdwx.qlogo.cn/mmopen/4wIU4IwY5dibkyVsg99ahreibZYPFEtGYSPebV0VeI59JNcIiaB33bdYm0mGAObB4QyMz0PgJpZ7sFH4Iw2zsVZicicjZnyE8wttR/132",
            "openid"       => "oWqQa6IyFXdosOx5uroFusiIUGz8",
            "Identity"     => 0,
            "remarks"      => "",
            "created_at"   => date('Y-m-d h:i:s', time()),
            "updated_at"   => date('Y-m-d h:i:s', time()),
        ]);
        DB::table("user")->insert([
            "username"   => "五哥潮鞋 正常接单",
            "head_img_url" => "http://thirdwx.qlogo.cn/mmopen/TxgpkT9ibpnHiah9foWlLkknjcJNLmOa19nqEgmnCsbicoyFYIGUZicHP5AVN0FbcIHyta8Tn3BSsMoXTWEsLQd3wgsmwa7mxdqib/132",
            "openid"     => "oWqQa6HraV3D-4FXZe09Q7WZPidk",
            "Identity"   => 0,
            "remarks"    => "",
            "created_at" => date('Y-m-d h:i:s', time()),
            "updated_at" => date('Y-m-d h:i:s', time()),
        ]);
    }
}
