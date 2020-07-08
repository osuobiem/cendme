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
        'password', 'remember_token',
    ];

    // Relationship with Product
    public function product()
    {
        return $this->hasMany('App\Product');
    }

    // Relationship with LGA
    public function lga()
    {
        return $this->belongsTo('App\Lga');
    }

    // Relationship with Vendor Account
    public function account()
    {
        return $this->hasOne('App\Vendor_account');
    }
}
