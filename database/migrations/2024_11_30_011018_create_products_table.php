<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('description')->nullable();
            $table->double('price',10,2);
            $table->double('compare_price',10,2)->nullable();
            $table->foreignIdFor(Category::class,'category_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(SubCategory::class,'sub_category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Brand::class,'brand_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('is_featured',['Yes','No'])->default('No');
            $table->string('sku')->unique();
            $table->string('barcode')->nullable();
            $table->enum('track_qty',['Yes','No'])->default('Yes');
            $table->integer('qty')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
