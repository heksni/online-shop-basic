<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,)
    {
        $brands = Brand::where('name','like','%'.$request->keyword.'%')
                        ->latest()
                        ->paginate(10);

        return view('admin.brand.list',compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'status' => 'required'
        ]);

        if($validator->passes()){
        // dd($validator->getData());
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            session()->flash('success','Brand added successfully!');

            return response([
                'status' => true,
                'message' => 'Brand added successfully!'
            ]);

        }else{

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }




    }

    public function edit($brandId)
    {
        $brand = Brand::find($brandId);

        if(!$brand){
            session()->flash('error','Brand no found');

            return response([
                'status' => false,
                'message' => 'Brand no found'
            ]);
        }
        return view('admin.brand.edit',compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $brandId)
    {
        $brand = Brand::find($brandId);

        if(empty($brand)){

            session()->flash('error','Brand not found');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Brand not found'
            ]);
        }

           $validator = Validator::make($request->all(),[
            'name' => 'required',
            'status' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id.',id',
        ]);


        if($validator->passes()){

            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            session()->flash('success','Brand updated successfully!');

            return response()->json([
                'status' => true,
                'message' => "Brand updated successfully"
            ]);

        } else{

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand = Brand::find($brand->id);

        if(empty($brand)){
            session()->flash('error','Category not found');

            return response()->json([
                'status' => false,
                'message' => 'Brand not found'
            ]);
        }

        $brand->delete();
        
        session()->flash('success','Brand deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Brand deleted successfully'
        ]);
    }
}
