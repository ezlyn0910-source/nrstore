<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin account
        User::updateOrCreate(
            ['email' => 'nrintellitech@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Admin123'),
                'phone' => '014-9840609',
                'status' => 'active',
                'role' => 'admin',
            ]
        );

        // Create a sample customer account
        User::updateOrCreate(
            ['email' => 'ezlyn0910@gmail.com'],
            [
                'name' => 'Ezlyn',
                'password' => Hash::make('Ezlyn0910@'),
                'phone' => '014-9840609',
                'status' => 'active',
                'role' => 'customer',
            ]
        );

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: nrintellitech@gmail.com');
        $this->command->info('Password: Admin123');
    }
}