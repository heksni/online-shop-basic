<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\File;



class ProductImageController extends Controller
{
    public function update(Request $request)
    {

        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();

        $prodImage = new ProductImage();
        $prodImage->product_id = $request->product_id;
        $prodImage->image = 'NULL';
        $prodImage->save();

        $imageName = $request->product_id.'-'.$prodImage->id.'-'.time().'.'.$ext;
        $prodImage->image = $imageName;
        $prodImage->save();

        //generate prod thumb
        //toLarge
        $destPatch = public_path().'/uploads/product/large/'.$imageName;
        $image = Image::read($sourcePath);
        $image->resize(1000,1400);
        $image->save($destPatch);

        //toSmall
        $destPatch = public_path().'/uploads/product/small/'.$imageName;
        $image = Image::read($sourcePath);
        $image->resize(300,300);
        $image->save($destPatch);

        return response()->json([
            'status' => true,
            'image_id' =>$prodImage->id,
            'ImagePath' => asset('uploads/product/small/'.$prodImage->image),
            'message' => 'Image saved successfully'
        ]);

    }

    public function destroy(Request $request)
    {
        
        $prodImage = ProductImage::find($request->id);

        if(empty($prodImage)){
            
            return response()->json([
                'status' => false,
                'message' => 'Image not found'
            ]);
        }

        //delete images from folder
        File::delete(public_path('uploads/product/large/'.$prodImage->image));
        File::delete(public_path('uploads/product/small/'.$prodImage->image));

        $prodImage->delete();

        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully'
        ]);
    }
}
