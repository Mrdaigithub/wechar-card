<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WinningLog extends Model {

    protected $table = "winning_log";

    public function user() {
        return $this->belongsToMany("App\Model\User",
            "winning_log_user",
            "winning_log_id",
            "user_id");
    }

    public function writeOffer() {
        return $this->belongsToMany("App\Model\User",
            "winning_log_write_offer",
            "winning_log_id",
            "write_offer_id");
    }

    public function card() {
        return $this->belongsToMany("App\Model\Card",
            "winning_log_card",
            "winning_log_id",
            "card_id");
    }

    public function activity() {
        return $this->belongsToMany("App\Model\Activity",
            "winning_log_activity",
            "winning_log_id",
            "activity_id");
    }
}
