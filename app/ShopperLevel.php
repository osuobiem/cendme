<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentLevel extends Model
{
    use SoftDeletes;

    protected $table = 'shopper_levels';
}
