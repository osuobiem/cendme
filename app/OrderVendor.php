<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderVendor extends Model
{
    // Relationship with Order
    public function order() {
        return $this->belongsTo('App\Order');
    }
}
