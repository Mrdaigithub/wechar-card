<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model {

  protected $table = "shop";

  public function activities() {
    return $this->belongsToMany("App\Model\Activity",
      "shop_activity",
      "shop_id",
      "activity_id");
  }
}
