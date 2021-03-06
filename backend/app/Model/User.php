<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject {
  
  protected $table = "user";
  
  public function signInLogs() {
    return $this->belongsToMany("App\Model\SignIn",
      "sign_in_user",
      "user_id",
      "sign_in_id");
  }
  
  public function cardList() {
    return $this->belongsToMany("App\Model\Card",
      "card_user",
      "user_id",
      "card_id");
  }

  public function shop() {
        return $this->belongsToMany("App\Model\Shop",
            "shop_user",
            "user_id",
            "shop_id");
    }
  
  /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier() {
    return $this->getKey();
  }
  
  /**
   * Return a key value array, containing any custom claims to be added to the
   * JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims() {
    return [];
  }
}
