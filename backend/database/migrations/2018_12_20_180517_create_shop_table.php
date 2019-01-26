<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('shop',
      function (Blueprint $table) {
        $table->bigIncrements('id')->unique();
        $table->string('shop_name')->comment("商家名");
        $table->string('shop_location')->comment("商家地址");
        $table->timestamp('started_at')
              ->default(date('Y-m-d h:m:s', time()))
              ->comment("合作时间");
        $table->boolean('state')
              ->default(FALSE)
              ->comment("商家状态(true:合作中、false:合作结束)");
        $table->string('remarks')->nullable()->comment("备注");
        $table->timestamps();
      });

    Schema::create("user_shop",
      function (Blueprint $table) {
        $table->bigInteger("user_id");
        $table->bigInteger("shop_id")->unique();
      });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('shop');
    Schema::dropIfExists('user_shop');
  }
}
