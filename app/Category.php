<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Relationship with SubCategory
    public function sub_category()
    {
        return $this->hasMany('App\SubCategory');
    }
}
