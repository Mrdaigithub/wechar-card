<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemConfigTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('system_config',
      function (Blueprint $table) {
        $table->bigIncrements('id')->unique();
        $table->string("config_name")->unique();
        $table->string("config_description");
        $table->string("config_value");
        $table->timestamps();
      });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('system_config');
  }
}
