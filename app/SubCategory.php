<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
