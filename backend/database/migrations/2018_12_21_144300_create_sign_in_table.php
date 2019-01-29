<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignInTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create("sign_in",
      function (Blueprint $table) {
        $table->bigIncrements("id");
        $table->text("month_sign_in_log")->comment("当前月份签到记录(天)");
        $table->timestamps();
      });
    Schema::create("user_sign_in",
      function (Blueprint $table) {
        $table->bigInteger("sign_in_id");
        $table->bigInteger("user_id");
      });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists("sign_in");
    Schema::dropIfExists("user_sign_in");
  }
}
