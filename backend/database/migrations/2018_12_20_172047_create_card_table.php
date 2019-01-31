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
      $table->string("card_thumbnail")
            ->default("https://randomuser.me/api/portraits/men/85.jpg")
            ->comment("卡券的缩略图");
      $table->timestamp('end_time_0')->nullable()->comment("指定时间的失效时间");
      $table->unsignedInteger('end_time_1')->nullable()
            ->comment("倒计时的失效时间(秒)");
      $table->double('probability')
            ->nullable()->default(0.0)->comment("概率 0-1");
      $table->boolean('state')->default(FALSE)->comment("状态 0.停用 1.启用");
      $table->boolean('type')->default(FALSE)->comment("卡券类型 0.样板卡券 1.使用的卡券");
      $table->unsignedBigInteger('parentid')->nullable()->comment("继承的样板卡券ID");
      $table->string('remarks')->nullable()->comment("备注");
      $table->timestamps();
    });
    
    Schema::create("card_user", function(Blueprint $table) {
      $table->bigInteger("card_id");
      $table->bigInteger("user_id");
    });
  }
  
  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('card');
    Schema::dropIfExists('card_user');
  }
}
