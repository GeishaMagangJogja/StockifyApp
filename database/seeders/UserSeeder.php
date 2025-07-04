<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@stockify.com'],
            [
                'name' => 'Admin Stockify',
                'password' => Hash::make('password'),
                'role' => 'Admin',
            ]
        );

        // Manajer Gudang
        User::updateOrCreate(
            ['email' => 'manager@stockify.com'],
            [
                'name' => 'Manajer Gudang',
                'password' => Hash::make('password'),
                'role' => 'Manajer Gudang',
            ]
        );

        // Staff Gudang
        User::updateOrCreate(
            ['email' => 'staff@stockify.com'],
            [
                'name' => 'Staff Gudang',
                'password' => Hash::make('password'),
                'role' => 'Staff Gudang',
            ]
        );
    }
}
