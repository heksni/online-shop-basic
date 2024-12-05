<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $guarded =[];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }    
    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function brand()
    {
        return $this->hasOne(Brand::class);
    }

    public function product_images()
    {
        return $this->hasOne(ProductImage::class);
    }
}
