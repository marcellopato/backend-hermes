<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserWithRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Managers
        for ($i = 1; $i <= 2; $i++) {
            $manager = User::create([
                'name' => "Manager $i",
                'email' => "manager$i@example.com",
                'password' => Hash::make('password'),
            ]);
            $manager->assignRole('manager');
        }

        // Vendors
        for ($i = 1; $i <= 3; $i++) {
            $vendor = User::create([
                'name' => "Vendor $i",
                'email' => "vendor$i@example.com",
                'password' => Hash::make('password'),
            ]);
            $vendor->assignRole('vendor');
        }

        // Usuários comuns
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => "User $i",
                'email' => "user$i@example.com",
                'password' => Hash::make('password'),
            ]);
            // Sem role específica
        }
    }
} 