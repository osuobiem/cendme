<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    // Relationship with OrderProduct
    public function order_product()
    {
        return $this->hasMany('App\OrderProduct');
    }

    // Relationship with Product
    public function product()
    {
        return $this->belongsToMany('App\Product')->withPivot('quantity');
    }
}
