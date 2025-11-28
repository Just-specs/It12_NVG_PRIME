<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin user
        User::firstOrCreate(
            ['email' => 'admin@dispatch.com'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@dispatch.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Create Head of Dispatch user
        User::firstOrCreate(
            ['email' => 'head@dispatch.com'],
            [
                'name' => 'Head of Dispatch',
                'email' => 'head@dispatch.com',
                'password' => Hash::make('head123'),
                'role' => 'head-of-dispatch',
            ]
        );

        // Create Dispatch User
        User::firstOrCreate(
            ['email' => 'user@dispatch.com'],
            [
                'name' => 'Dispatch User',
                'email' => 'user@dispatch.com',
                'password' => Hash::make('user123'),
                'role' => 'dispatch-user',
            ]
        );

        $this->command->info('? Role-based users created successfully!');
        $this->command->info('  Admin: admin@dispatch.com / admin123');
        $this->command->info('  Head of Dispatch: head@dispatch.com / head123');
        $this->command->info('  Dispatch User: user@dispatch.com / user123');
    }
}
