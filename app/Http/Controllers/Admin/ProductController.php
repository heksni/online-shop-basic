<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\File;



class ProductController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        $products = Product::with('product_images')->where('title','like','%'.$keyword.'%')
                            ->orWhere('sku','like','%'.$keyword.'%')
                            ->latest()
                            ->simplePaginate(6); 




        return view('admin.product.list',compact('products'));
    }

    public function create()
    {
        $products = Product::all(); 
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();

        return view('admin.product.create',compact('products','categories','brands'));
    }

    public function store(Request $request)
    {

            $rules = ([
                'title' => 'required',
                'slug' => 'required|unique:products',
                'price' => 'required|numeric',
                'sku' => 'required|unique:products',
                'track_qty' => 'required|in:Yes,No',
                'category_id' => 'required|numeric',
                'is_featured' => 'required|in:Yes,No'
            ]);
            
            if($request->track_qty && $request->track_qty == 'Yes'){
                $rules['qty'] = 'required|numeric';
            }

            $validator = Validator::make($request->all(),$rules);
                        
            if($validator->passes()){

                $product = new Product();
                $product->title = $request->title;
                $product->slug = $request->slug;
                $product->description = $request->description;
                $product->price = $request->price;
                $product->compare_price = $request->compare_price;
                $product->sku = $request->sku;
                $product->barcode = $request->barcode;
                $product->track_qty = $request->track_qty;
                $product->qty = $request->qty;
                $product->status = $request->status;
                $product->category_id = $request->category_id;
                $product->sub_category_id = $request->sub_category_id;
                $product->brand_id = $request->brand_id;
                $product->is_featured = $request->is_featured;
                $product->save();

                if(!empty($request->image_array)){

                    foreach($request->image_array as $temp_image_id){

                        $tempImageInfo = TempImage::find($temp_image_id);
                        $exArray = explode('.',$tempImageInfo->name);
                        $ext = last($exArray);  
                        
                        $prodImage = new ProductImage();
                        $prodImage->product_id = $product->id;
                        $prodImage->image = 'NULL';
                        $prodImage->save();

                        $imageName = $product->id.'-'.$prodImage->id.'-'.time().'.'.$ext;

                        $prodImage->image = $imageName;
                        $prodImage->save();

                        //generate prod thumb
                        //toLarge
                        $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                        $destPatch = public_path().'/uploads/product/large/'.$imageName;
                        $image = Image::read($sourcePath);
                        $image->resize(800,900);
                        $image->save($destPatch);

                        //toSmall
                        $destPatch = public_path().'/uploads/product/small/'.$imageName;
                        $image = Image::read($sourcePath);
                        $image->resize(300,300);
                        $image->save($destPatch);


                    }
                }

                session()->flash('success','Product added successfully');

                return response()->json([
                    'status' => true,
                    'message' => 'Product added successfully'
                ]);

            } else{
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
    }

    public function edit(Product $product)
    {

        if(empty($product)){
            return redirect()->route('products.index')->with('error','Product not Found');
        }

        $prodImages = ProductImage::where('product_id',$product->id)->get();

        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        $sub_categories = SubCategory::orderBy('name','ASC')->get();
        $product->with('category','sub_category','brand');

        return view('admin.product.edit',compact('product','categories','brands','sub_categories','prodImages'));
    }

    public function update(Product $product, Request $request)
    {
        $rules = ([
            'title' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id.'id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,'.$product->id.'id',
            'track_qty' => 'required|in:Yes,No',
            'category_id' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No'
        ]);

        if($request->track_qty && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(),$rules);

        if($validator->passes()){

            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category_id;
            $product->brand_id = $request->brand_id;
            $product->is_featured = $request->is_featured;
            $product->save();


            session()->flash('success','Product updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        } 
    }

    public function destroy(Product $product)
    {

        $prodImage = ProductImage::where('product_id',$product->id)->get('image');

            if(empty($product)){
                session()->flash('error','Product not found');

                return response()->json([
                    'status' => false,
                    'notFound'=>false,
                    'message' => 'Product not found'
                ]);
            }

            //Delete old images
        foreach($prodImage as $image){
            File::delete(public_path().'/uploads/product/large/'.$image->image);
            File::delete(public_path().'/uploads/product/small/'.$image->image);
        }
            
            $product->delete();
            
            session()->flash('success','Product deleted successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product deleted successfully'
            ]);

    }
}
