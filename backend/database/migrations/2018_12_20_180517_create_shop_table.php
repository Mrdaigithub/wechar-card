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
      function(Blueprint $table) {
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
    
    Schema::create("shop_activity",
      function(Blueprint $table) {
        $table->bigInteger("shop_id")->unique();
        $table->bigInteger("activity_id")->unique();
      });
    
    Schema::create("shop_user",
      function(Blueprint $table) {
        $table->bigInteger("shop_id")->unique();
        $table->bigInteger("user_id");
      });
  }
  
  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('shop');
    Schema::dropIfExists('shop_activity');
    Schema::dropIfExists('shop_user');
  }
}
