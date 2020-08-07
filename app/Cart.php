<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    // Relationship with Product
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
