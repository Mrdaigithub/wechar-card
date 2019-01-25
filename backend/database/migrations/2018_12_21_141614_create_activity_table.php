<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create("activity", function(Blueprint $table) {
			$table->increments("id");
			$table->string("activity_name")->comment("活动名");
			$table->string("activity_description")->nullable()->comment("活动详情");
			$table->string("activity_thumbnail")->nullable()->comment("活动的缩略图");
			$table->timestamp("start_time")->nullable()->comment("活动开始日期");
			$table->timestamp("end_time")->nullable()->comment("活动结束日期");
			$table->boolean("state")->nullable()->comment("活动状态(启用或者停用)");
			$table->integer("customer_num")->nullable()->comment("参与人数");
			$table->string('remarks')->nullable()->comment("备注");
			$table->string('reply_keyword')->unique()->nullable()->comment("回复关键词");
			$table->timestamps();
		});
		
		Schema::create("shop_activity", function(Blueprint $table) {
			$table->integer("shop_id");
			$table->increments("activity_id")->unique();
		});
		
		Schema::create("card_activity", function(Blueprint $table) {
			$table->integer("card_id");
			$table->increments("activity_id")->unique();
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists("activity");
		Schema::dropIfExists("shop_activity");
		Schema::dropIfExists("card_activity");
	}
}
