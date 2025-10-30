<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'HP', 'slug' => 'hp'],
            ['name' => 'Dell', 'slug' => 'dell'],
            ['name' => 'Microsoft', 'slug' => 'microsoft'],
            ['name' => 'Lenovo', 'slug' => 'lenovo'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}