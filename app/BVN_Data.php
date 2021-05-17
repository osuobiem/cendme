<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BVN_Data extends Model
{
    use SoftDeletes;

    protected $table = 'bvn_data';
}
