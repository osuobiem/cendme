<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    // Relationship with Product
    public function product()
    {
        return $this->belongsToMany('App\Product')->withPivot('quantity');
    }

    // Relationship with user
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // Relationship with Shopper
    public function shopper()
    {
        return $this->belongsTo('App\Shopper');
    }

    // Relationship with OrderVendors
    public function order_vendors()
    {
        return $this->hasMany('App\OrderVendor');
    }
}
