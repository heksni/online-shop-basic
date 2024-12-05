<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        $subCategories = SubCategory::whereHas('category', function ($query) use ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        })->orWhere('name','like','%'.$keyword.'%')->latest()->paginate(10);

        if(!$subCategories){
            session()->flash('error','Sub Category not found');

            return redirect()->route('sub.categories.index');

        }

        return view('admin.sub_category.list',compact('subCategories'));
    }

    public function create(Request $request)
    {
        $categories = Category::orderBy('name','ASC')->get();

        return view('admin.sub_category.create',compact('categories'));
    }

    public function store(Request $request)
    {
        $subCat = SubCategory::where('slug', $request->slug)->first(); // Get the first matching record

        $sub = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,' . ($subCat ? $subCat->id : 'NULL') . ',id',
            'category_id' => 'required',
            'show_home' => 'required',
            'status' => 'required'
        ]);
        
        if($sub){
            
            SubCategory::create($sub);
        
            session()->flash('success','Sub Category added successfully');

            return response([
                'status' => true,
                'message' => "Sub Category added successfully"
            ]);

        } else {

            return response()->json([
                'status' => false,
                // 'errors' => $validator->errors(),
                'message' => "Sub Category was not added successfully"
            ]);
        }

    }

    public function edit(Request $request, $subCategoryId)
    {
        $categories = Category::orderBy('name','ASC')->get();
        $subCategory = SubCategory::find($subCategoryId);

        if(empty($subCategory)){
            session()->flash('error','Sub Category not found');

            return redirect()->route('sub.categories.index');

        }

        return view('admin.sub_category.edit',compact('subCategory','categories'));
    }

    public function update(Request $request,$subCategoryId)
    {
        $subCategory = SubCategory::find($subCategoryId);

        if(empty($subCategory)){

            session()->flash('error','Sub Category not found');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Sub Category not found'
            ]);
        }

           $validator = Validator::make($request->all(),[
            'name' => 'required',
            'category_id' => 'required',
            'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
        ]);

        if($validator->passes()){
        
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->show_home = $request->show_home;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category_id;
            $subCategory->save();

            // $oldImage = $subCategory->image;

            //samve image 
            // if(!empty($request->image_id)){
            //     $tempImage = TempImage::find($request->image_id);
            //     $extArray = explode('.',$tempImage->name);
            //     $ext = last($extArray);

            //     $newImageName = $category->id.'-'.time().'.'.$ext;
            //     $sPath = public_path().'/temp/'.$tempImage->name;
            //     $dPath = public_path().'/uploads/category/'.$newImageName;

            //     File::copy($sPath,$dPath);

            //     //generate image thumbnail by using "composer require intervention/image"

            //     $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
            //     // $img = Image::read($sPath);
            //     // $img->resize(450,600);
            //     // $img->save($dPath);


            //     $subCategory->image = $newImageName;
            //     $subCategory->save();


            //     //Delete old images
            //     File::delete(public_path().'/uploads/category/thumb/'.$oldImage);
            //     File::delete(public_path().'/uploads/category/'.$oldImage);
            // }
   
            session()->flash('success','Sub Category updated successfully');

            return response()->json([
                'status' => true,
                'message' => "Sub Category updated successfully"
            ]);

        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }
    public function destroy($subCategoryId)
    {
        $sub = SubCategory::find($subCategoryId);

        if(empty($sub)){
            session()->flash('error','Sub Category not found');

            return response()->json([
                'status' => false,
                'message' => 'Sub Category not found'
            ]);
        }

        //Delete old images
        // File::delete(public_path().'/uploads/category/thumb/'.$sub->image);
        // File::delete(public_path().'/uploads/category/'.$sub->image);
        
        $sub->delete();
        
        session()->flash('success','Sub Category deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Sub Category deleted successfully'
        ]);


    }
}
