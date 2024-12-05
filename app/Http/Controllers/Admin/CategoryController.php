<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;


class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('name', 'LIKE', '%' . $request->keyword . '%')->latest()->paginate(10);

        return view('admin.category.list', ['categories' => $categories]);
    }
    public function create()
    {

        return view('admin.category.create');
    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->show_home = $request->show_home;
            $category->status = $request->status;
            $category->save();

            //samve image 
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;

                File::copy($sPath, $dPath);

                //generate image thumbnail by using "composer require intervention/image"

                $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
                $img = Image::read($sPath);
                $img->resize(450, 600);
                $img->save($dPath);


                $category->image = $newImageName;
                $category->save();
            }

            session()->flash('success', 'Category added successfully');

            return response()->json([
                'status' => true,
                'message' => "Category added successfully"
            ]);
        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($categoryId, Request $request)
    {

        $category = Category::find($categoryId);

        if (empty($category)) {
            session()->flash('error', 'Category not found');

            return redirect()->route('categories.index');
        }

        return view('admin.category.edit', compact('category'));
    }
    public function update($categoryId, Request $request)
    {

        $category = Category::find($categoryId);

        if (empty($category)) {

            session()->flash('error', 'Category not found');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id . ',id',
        ]);

        if ($validator->passes()) {

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->show_home = $request->show_home;
            $category->status = $request->status;
            $category->save();

            $oldImage = $category->image;

            //samve image 
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '-' . time() . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;

                File::copy($sPath, $dPath);

                //generate image thumbnail by using "composer require intervention/image"

                $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
                $img = Image::read($sPath);
                $img->resize(500, 600);
                $img->save($dPath);


                $category->image = $newImageName;
                $category->save();


                //Delete old images
                File::delete(public_path() . '/uploads/category/thumb/' . $oldImage);
                File::delete(public_path() . '/uploads/category/' . $oldImage);
            }

            session()->flash('success', 'Category updated successfully');

            return response()->json([
                'status' => true,
                'message' => "Category updated successfully"
            ]);
        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy($categoryId)
    {
        $category = Category::find($categoryId);

        if (empty($category)) {
            session()->flash('error', 'Category not found');

            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ]);
        }

        //Delete old images
        File::delete(public_path() . '/uploads/category/thumb/' . $category->image);
        File::delete(public_path() . '/uploads/category/' . $category->image);

        $category->delete();

        session()->flash('success', 'Category deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}
