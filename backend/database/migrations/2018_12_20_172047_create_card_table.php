<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardTable extends Migration {
  
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('card', function(Blueprint $table) {
      $table->bigIncrements('id')->unique();
      $table->string('card_name');
      $table->timestamp('end_time')->nullable()->comment("失效时间");
      $table->boolean('state')->default(FALSE)->comment("状态 0.停用 1.启用");
      $table->double('probability')->default(0.0)->comment("概率 0-1");
      $table->integer('all_num')->default(0)->comment("总计数量");
      $table->integer('usable_num')->default(0)->comment("可用数量");
      $table->integer('used_num')->default(0)->comment("参与人数，使用过的数量");
      $table->string('remarks')->nullable()->comment("备注");
      $table->timestamps();
    });
    
    Schema::create("user_card", function(Blueprint $table) {
      $table->bigInteger("user_id");
      $table->bigInteger("card_id");
    });
  }
  
  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('card');
    Schema::dropIfExists('user_card');
  }
}
