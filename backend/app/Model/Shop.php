<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model {

    protected $table = "shop";

    public function activity() {
        return $this->belongsToMany("App\Model\Activity",
            "shop_activity",
            "shop_id",
            "activity_id");
    }

    public function users() {
        return $this->belongsToMany("App\Model\User",
            "shop_user",
            "shop_id",
            "user_id");
    }
}
