<?php

use App\Models\Category;
use App\Models\Product;

function getCategories()
{
    return Category::orderBy('name','ASC')
                    ->with('subcategories')
                    ->where('show_home','Yes')
                    ->where('status','1')
                    ->get();
}


function latestProducts()
{
    return Product::orderBy('id','DESC')
                        ->with('product_images')
                        ->where('status',1)
                        ->latest()
                        ->take(5)
                        ->get();
}