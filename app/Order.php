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
}
