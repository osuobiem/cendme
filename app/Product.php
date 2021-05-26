<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable=['title', 'details', 'quantity', 'price', 'vendor_id', 'subcategory_id'];
    // Relationship with Vendor
    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }

    // Relationship with subcategory
    public function subcategory()
    {
        return $this->belongsTo('App\SubCategory');
    }
}
