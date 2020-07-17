<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Agent extends Authenticatable
{
    use HasApiTokens;
    use SoftDeletes;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Relationship with LGA
    public function lga()
    {
        return $this->belongsTo('App\Lga');
    }


    // Relationship with Level
    public function level()
    {
        return $this->belongsTo('App\AgentLevel');
    }

    /**
     * Relationship with BVN Data
     */
    public function bvn_data()
    {
        return $this->hasOne('App\BVN_Data');
    }
}
