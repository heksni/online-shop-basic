<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 2,
            'password' => Hash::make('qweqwe')
        ]);

        User::factory()->create([
            'name' => 'Guest',
            'email' => 'guest@example.com',
            'role' => 1,
            'password' => Hash::make('qweqwe')
        ]);

        \App\Models\Category::factory(10)->create();
        \App\Models\SubCategory::factory(30)->create();
        \App\Models\Brand::factory(5)->create();
        \App\Models\Product::factory(100)->create();

    }
}
