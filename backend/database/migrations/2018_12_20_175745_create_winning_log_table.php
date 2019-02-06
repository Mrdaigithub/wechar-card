<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWinningLogTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('winning_log', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->string('location')->comment("用户中奖位置");
            $table->boolean('write_off_state')->default(FALSE)->comment("核销状态");
            $table->dateTime('write_off_date')->nullable()->comment("核销时间");
            $table->string('remarks')->nullable()->comment("备注");
            $table->timestamps();
        });

        Schema::create("winning_log_user", function (Blueprint $table) {
            $table->bigInteger("user_id");
            $table->bigInteger("winning_log_id")->unique();
        });

        Schema::create("winning_log_write_offer", function (Blueprint $table) {
            $table->bigInteger("write_offer_id");
            $table->bigInteger("winning_log_id")->unique();
        });

        Schema::create("winning_log_card", function (Blueprint $table) {
            $table->bigInteger("card_id");
            $table->bigInteger("winning_log_id")->unique();
        });

        Schema::create("winning_log_activity", function (Blueprint $table) {
            $table->bigInteger("activity_id");
            $table->bigInteger("winning_log_id")->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('winning_log');
        Schema::dropIfExists('winning_log_user');
        Schema::dropIfExists('winning_log_write_offer');
        Schema::dropIfExists('winning_log_card');
        Schema::dropIfExists('winning_log_activity');
    }
}
