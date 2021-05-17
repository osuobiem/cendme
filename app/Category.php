<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    // Relationship with SubCategory
    public function subcategory()
    {
        return $this->hasMany('App\SubCategory');
    }

    // Relationship with product
    public function product()
    {
        return $this->hasManyThrough('App\Product', 'App\SubCategory', 'category_id', 'subcategory_id');
    }
}
