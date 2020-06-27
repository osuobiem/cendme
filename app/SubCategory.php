<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use SoftDeletes;

    protected $table = 'subcategories';

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
