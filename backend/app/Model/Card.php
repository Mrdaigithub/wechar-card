<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Card extends Model {

    protected $table = "card";

    public function user() {
        return $this->belongsToMany("App\Model\User",
            "card_user",
            "card_id",
            "user_id");
    }

    public function activity() {
        return $this->belongsToMany("App\Model\Activity",
            "card_activity",
            "card_id",
            "activity_id");
    }

    public function winningLog() {
        return $this->belongsToMany("App\Model\WinningLog",
            "winning_log_card",
            "card_id",
            "winning_log_id");
    }
}
