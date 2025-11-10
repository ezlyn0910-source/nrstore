<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Remove these factory calls since your User model has custom fields
        // User::factory(10)->create();
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Only call your custom seeders
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            AdminSeeder::class,
        ]);
    }
}