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
}
