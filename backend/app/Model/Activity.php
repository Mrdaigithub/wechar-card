<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model {

  protected $table = "activity";

  public function shops() {
    return $this->belongsToMany("App\Model\Shop",
      "shop_activity",
      "activity_id",
      "shop_id");
  }

  public function cards() {
    return $this->belongsToMany("App\Model\Card",
      "card_activity",
      "activity_id",
      "card_id");
  }

    public function winningLogs() {
        return $this->belongsToMany("App\Model\WinningLog",
            "winning_log_activity",
            "activity_id",
            "winning_log_id");
    }
}
