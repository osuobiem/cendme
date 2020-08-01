<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use SoftDeletes;

    // Relationship with State
    public function state()
    {
        return $this->belongsTo('App\State');
    }
}
