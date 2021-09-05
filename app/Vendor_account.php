<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor_account extends Model
{
    use SoftDeletes;

    // Relationship with Bank
    public function bank()
    {
        return $this->belongsTo('App\Bank');
    }
}
