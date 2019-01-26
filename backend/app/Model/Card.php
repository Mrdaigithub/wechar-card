<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Card extends Model {

  protected $table  = "card";

  protected $hidden = ["probability", "all_num", "usable_num", "used_num"];
}
