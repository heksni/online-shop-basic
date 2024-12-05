<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;


class TempImagesController extends Controller
{
    public function create(Request $request)
    {
        
        $image = $request->image;
        
        if(!empty($image)){
            
            $ext = $image->getClientOriginalExtension();
            // $newName = time().'.'.$ext;


            $newName = Str::random(20) . '.' . $ext; // Generates a random 40-character string
      
            // Increment file name until a unique name is found
            while (file_exists(public_path('/temp/' . $newName))) {
                $newName = Str::random(20) . '.' . $ext;
            }

            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();

            $image->move(public_path().'/temp',$newName);

            //Generate Thumbnail
            $sourcePath = public_path().'/temp/'.$newName;
            $destinationPath = public_path().'/temp/thumb/'.$newName;
            $image = Image::read($sourcePath);
            $image->resize(300,275);
            $image->save($destinationPath);



            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('/temp/thumb/'.$newName),
                'message' => 'Image uploaded successfully!'
            ]);
        }

       
    }
}
