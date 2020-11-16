<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderVendor extends Model
{
    protected $table = 'order_vendors';

    // Relationship with Order
    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}
