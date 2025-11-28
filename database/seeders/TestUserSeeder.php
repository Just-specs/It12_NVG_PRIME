<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user (Head of Dispatch with admin role)
        User::updateOrCreate(
            ['email' => 'admin@dispatch.com'],
            [
                'name' => 'Admin / Head of Dispatch',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Create another head of dispatch user
        User::updateOrCreate(
            ['email' => 'head@dispatch.com'],
            [
                'name' => 'Head of Dispatch',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Create dispatch user
        User::updateOrCreate(
            ['email' => 'dispatch@dispatch.com'],
            [
                'name' => 'Dispatch User',
                'password' => Hash::make('password123'),
                'role' => 'dispatch-user',
            ]
        );
    }
}
