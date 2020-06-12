<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes;

    // Relationship with Lga
    public function lga()
    {
        return $this->hasMany('App\Lga');
    }
}
