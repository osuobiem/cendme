<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Vendor extends Authenticatable
{
    use SoftDeletes;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'orders_count'
    ];

    // Relationship with Product
    public function product()
    {
        return $this->hasMany('App\Product');
    }

    // Relationship with Area
    public function area()
    {
        return $this->belongsTo('App\Area');
    }

    // Relationship with Vendor Account
    public function account()
    {
        return $this->hasOne('App\Vendor_account');
    }
}
