<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Shopper extends Authenticatable
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


    // Relationship with Level
    public function level()
    {
        return $this->belongsTo('App\ShopperLevel');
    }

    /**
     * Relationship with BVN Data
     */
    public function bvn_data()
    {
        return $this->hasOne('App\BVN_Data');
    }

    /**
     * Relationship with Order
     */
    public function orders()
    {
        return $this->hasMany('App\Order');
    }
}
