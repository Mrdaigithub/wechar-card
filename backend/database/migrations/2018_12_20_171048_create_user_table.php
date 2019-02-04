<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create("user", function (Blueprint $table) {
      $table->bigIncrements("id")->unique();
      $table->string("username");
      $table->string("real_name")->nullable();
      $table->string("head_img_url")
            ->default("http://thirdwx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0");
      $table->string("phone")->nullable();
      $table->string("openid")->unique();
      $table->integer("lottery_num")->default(0)->comment("抽奖次数  (普通客户独有)");
      $table->integer("identity")
            ->default(0)
            ->comment("身份 0.普通客户 1.老板 2.员工 3.后台管理员");
      $table->string('remarks')->nullable()->comment("备注");

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists("user");
  }
}
