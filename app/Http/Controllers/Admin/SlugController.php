<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class SlugController extends Controller
{
   public function slug(Request $request)
   {

        $validator = Validator::make($request->all(),[
            'slug' => 'unique|sub_categories'
        ]);

        if($validator->passes()){


            $slug = '';
            
            if(!empty($request->title)){
                $slug = Str::slug($request->title);
            }
            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        }else{

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

   }
}
