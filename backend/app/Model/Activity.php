<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model {
	protected $table = "activity";
	
	public function shops() {
		return $this->belongsToMany("App\Model\Shop", "shop_activity", "activity_id", "shop_id");
	}
}
