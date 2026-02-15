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
            'perusahaan' => 'FC Network',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Teknisi
        User::create([
            'name' => 'Teknisi 1',
            'email' => 'teknisi1@fc-network.com',
            'phone' => '081234567891',
            'perusahaan' => 'FC Network Support',
            'password' => Hash::make('password'),
            'role' => 'teknisi',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Teknisi 2',
            'email' => 'teknisi2@fc-network.com',
            'phone' => '081234567892',
            'perusahaan' => 'FC Network Support',
            'password' => Hash::make('password'),
            'role' => 'teknisi',
            'is_active' => true,
        ]);

        // Users (Customers)
        User::create([
            'name' => 'User 1',
            'email' => 'user1@fc-network.com',
            'phone' => '081234567893',
            'perusahaan' => 'PT Pertamina gas',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'User 2',
            'email' => 'user2@fc-network.com',
            'phone' => '081234567894',
            'perusahaan' => 'PT Pertamina gas',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
        ]);
    }
}
