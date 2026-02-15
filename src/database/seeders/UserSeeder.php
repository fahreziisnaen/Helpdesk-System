<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@fc-network.com',
            'phone' => '081234567890',
            'company' => 'FC Network',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Teknisi
        User::create([
            'name' => 'Teknisi 1',
            'email' => 'teknisi1@fc-network.com',
            'phone' => '081234567891',
            'company' => 'FC Network Support',
            'password' => Hash::make('password'),
            'role' => 'teknisi',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Teknisi 2',
            'email' => 'teknisi2@fc-network.com',
            'phone' => '081234567892',
            'company' => 'FC Network Support',
            'password' => Hash::make('password'),
            'role' => 'teknisi',
            'is_active' => true,
        ]);

        // Users (Customers)
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'customer1@example.com',
            'phone' => '081234567893',
            'company' => 'PT Maju Jaya',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Siti Aminah',
            'email' => 'customer2@example.com',
            'phone' => '081234567894',
            'company' => 'CV Sejahtera',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
        ]);
    }
}
