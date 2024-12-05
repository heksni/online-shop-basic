<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Nette\Utils\Random;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubCategory>
 */
class SubCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $slug = '';
        $name = fake()->name();
        $category = Category::inRandomOrder()->value('id');
        return [
            'name' => $name,
            'status' => rand(0,1),
            'slug' => Str::slug($name),
            'category_id' => $category
        ];
    }
}
