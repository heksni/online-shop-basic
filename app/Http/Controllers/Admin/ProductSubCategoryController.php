<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductSubCategoryController extends Controller
{
    public function index(Request $request)
    {
        if($request->category_id){
            $subCategories = SubCategory::where('category_id',$request->category_id)
                                    ->orderBY('name','ASC')
                                    ->get();
    
                return response()->json([
                    'status' => true,
                    'subCategories' => $subCategories
                ]);

        } else{
            
            return response()->json([
                'status' => true,
                'subCategories' => []
            ]);
        }
    }
}
