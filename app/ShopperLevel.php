<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopperLevel extends Model
{
    use SoftDeletes;

    protected $table = 'shopper_levels';
}
