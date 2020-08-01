<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes;

    // Relationship with area
    public function area()
    {
        return $this->hasMany('App\Area');
    }
}
