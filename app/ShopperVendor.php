<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopperVendor extends Model
{
    
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'shopper_vendor';
}
