<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $table = 'categories'; // Ensure this is correct

    protected $fillable = [
        'name','slug','status','image'
    ];

    public function subcategories()
    {
        return $this->hasMany(SubCategory::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
