<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWinningLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('winning_log', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->string('winning_log_name');
            $table->string('gps')->comment("用户中奖位置");
            $table->boolean('write_off')->default(false)->comment("核销状态");
            $table->dateTime('write_off_date')->nullable()->comment("核销时间");
            $table->string('remarks')->nullable()->comment("备注");
            $table->timestamps();
        });

        Schema::create("user_winning_log", function (Blueprint $table) {
            $table->bigInteger("user_id");
            $table->bigInteger("winning_log_id")->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('winning_log');
        Schema::dropIfExists('user_winning_log');
    }
}
