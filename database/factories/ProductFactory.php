<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Nette\Utils\Random;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->jobTitle();
        $category = Category::inRandomOrder()->value('id');
        $subcat = SubCategory::inRandomOrder()->value('id');
        $brand = Brand::inRandomOrder()->value('id');

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit.',
            'price' =>rand(10,1000),
            'compare_price'=> 0,
            'category_id' => $category,
            'sub_category_id' => $subcat,
            'brand_id' => $brand,
            'is_featured' => 'No',
            'sku' => 'UGG-BB-PUR-'.rand(10,10000),
            'barcode' => '',
            'track_qty' => 'Yes',
            'qty' => rand(0,10),
            'status' => rand(0,1)
        ];
    }
}
