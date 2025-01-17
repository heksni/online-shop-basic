<?php

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TempImagesController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductSubCategoryController;
use App\Http\Controllers\Admin\SlugController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/',[FrontController::class,'index'])->name('front.home');
Route::get('/shop/{category?}/{subcategory?}/{id?}',[ShopController::class,'index'])->name('front.shop');



Route::group(['prefix' => 'admin'], function(){

    Route::group(['middleware' => 'admin.guest'],function(){

        Route::get('/login', [AdminLoginController::class, 'login'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

    });

    Route::group(['middleware' => 'admin.auth'],function(){
        
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        // Category
        Route::get('/categories',[CategoryController::class,'index'])->name('categories.index');
        Route::get('/categories/create',[CategoryController::class,'create'])->name('categories.create');
        Route::post('/categories',[CategoryController::class,'store'])->name('categories.store');
        
        Route::get('/categories/{category}/edit',[CategoryController::class,'edit'])->name('categories.edit');
        Route::put('/categories/{category}',[CategoryController::class,'update'])->name('categories.update');
        Route::delete('/categories/{category}',[CategoryController::class,'destroy'])->name('categories.delete');

        //temp-image route
        Route::post('/upload-temp-image',[TempImagesController::class,'create'])->name('temp-image.create');

        //get slug
        Route::get('/getSlug',[SlugController::class,'slug'])->name('getSlug');


        //Sub Category
        Route::get('/sub-categories',[SubCategoryController::class,'index'])->name('sub.categories.index');
        Route::get('/sub-categories/create',[SubCategoryController::class,'create'])->name('sub.categories.create');
        Route::post('/sub-categories/store',[SubCategoryController::class,'store'])->name('sub.categories.store');
        Route::get('/sub-categories/{subCategory}/edit',[SubCategoryController::class,'edit'])->name('sub.categories.edit');
        Route::put('/sub-categories/{subCategory}',[SubCategoryController::class,'update'])->name('sub.categories.update');
        Route::delete('/sub-categories/{subCategory}',[SubCategoryController::class,'destroy'])->name('sub.categories.delete');

        //Brands
        Route::get('/brands',[BrandController::class,'index'])->name('brands.index');
        Route::get('/brands/create',[BrandController::class,'create'])->name('brands.create');
        Route::post('/brands/store',[BrandController::class,'store'])->name('brands.store');
        Route::get('/brands/{brand}/edit',[BrandController::class,'edit'])->name('brands.edit');
        Route::put('/brands/{brand}',[BrandController::class,'update'])->name('brands.update');
        Route::delete('/brands/{brand}',[BrandController::class,'destroy'])->name('brands.delete');

        //Products
        Route::get('/products',[ProductController::class,'index'])->name('products.index');
        Route::get('/products/create',[ProductController::class,'create'])->name('products.create');
        Route::post('/products/store',[ProductController::class,'store'])->name('products.store');
        Route::get('/products/{product}/edit',[ProductController::class,'edit'])->name('products.edit');
        Route::put('/products/{product}',[ProductController::class,'update'])->name('products.update');
        Route::delete('/products/{product}',[ProductController::class,'destroy'])->name('products.delete');
        
        Route::get('/product-sub-categories',[ProductSubCategoryController::class,'index'])->name('products.sub.categories.index');
       
        Route::post('/product-images/update',[ProductImageController::class,'update'])->name('products.images.update');
        Route::delete('/product-images/delete',[ProductImageController::class,'destroy'])->name('products.images.delete');
    });
});