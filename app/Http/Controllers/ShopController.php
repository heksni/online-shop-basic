<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request,$categorySlug = null,$subcategorySlug = null, $id = null)
    {
        $categorySelected = '';
        $subCategorySelected = '';

        $categories = Category::with('subcategories')->orderBY('name','ASC')->where('status',1)->get();
        $brands = Brand::orderBY('name','ASC')->where('status',1)->get();

        $products = Product::where('status',1);
        //                     ->where(function ($query) use ($subcategorySlug) {
        //                         $query->orWhereHas('sub_category', function ($q) use ($subcategorySlug) {
        //                             $q->where('slug', $subcategorySlug);
        //                         });
        //                     })
        //                     ->get();
        //                     // ->get();

        // if(!empty($categorySlug)){

        if($categorySlug){

            $category = Category::where('slug',$categorySlug)->first();
            $products = $products->where('category_id',$category->id);
            $categorySelected = $category->id;
        }

        if(!empty($id)){
            $products = $products->where('sub_category_id',$id);

        }
    
        $products = $products->paginate(10);

        return view('front.shop',compact('categories','brands','products','categorySelected','subCategorySelected'));
    }

   
}



