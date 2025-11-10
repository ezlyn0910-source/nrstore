<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all();
        
        $products = [
            [
                'name' => 'HP Pavilion 15',
                'description' => 'Powerful laptop for everyday use',
                'price' => 799.99,
                'image' => 'storage/app/public/products/hp1.png',
                'category_id' => $categories->where('slug', 'hp')->first()->id,
                'brand' => 'HP',
                'ram' => '8GB',
                'storage' => '256GB SSD',
                'processor' => 'Intel Core i5',
                'stock_quantity' => 50,
                'is_recommended' => true,
            ],
            [
                'name' => 'Dell XPS 13',
                'description' => 'Premium ultrabook with stunning display',
                'price' => 1299.99,
                'image' => 'storage/app/public/products/dell1.png',
                'category_id' => $categories->where('slug', 'dell')->first()->id,
                'brand' => 'Dell',
                'ram' => '16GB',
                'storage' => '512GB SSD',
                'processor' => 'Intel Core i7',
                'stock_quantity' => 30,
                'is_recommended' => true,
            ],
            [
                'name' => 'Microsoft Surface Laptop 5',
                'description' => 'Sleek design with excellent performance',
                'price' => 1199.99,
                'image' => 'storage/app/public/products/microsoft1.png',
                'category_id' => $categories->where('slug', 'microsoft')->first()->id,
                'brand' => 'Microsoft',
                'ram' => '8GB',
                'storage' => '256GB SSD',
                'processor' => 'Intel Core i5',
                'stock_quantity' => 25,
                'is_recommended' => false,
            ],
            [
                'name' => 'Lenovo ThinkPad X1',
                'description' => 'Business laptop with superior keyboard',
                'price' => 1499.99,
                'image' => 'storage/app/public/products/lenovo1.png',
                'category_id' => $categories->where('slug', 'lenovo')->first()->id,
                'brand' => 'Lenovo',
                'ram' => '16GB',
                'storage' => '1TB SSD',
                'processor' => 'Intel Core i7',
                'stock_quantity' => 20,
                'is_recommended' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}