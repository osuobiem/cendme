<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Agent extends Authenticatable
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
