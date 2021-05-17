<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens;
  use SoftDeletes;

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  // Relationship with Area
  public function area()
  {
    return $this->belongsTo('App\Area');
  }

  // Relationship with Cart
  public function cart()
  {
    return $this->hasMany('App\Cart');
  }

  // Relationship with Orders
  public function orders()
  {
    return $this->hasMany('App\Order');
  }
}
