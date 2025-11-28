<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Basic test user (create if not exists)
        User::updateOrCreate(
            ['email' => 'john@gmail.com'],
            [
                'name' => 'Test User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'dispatch-user',
            ]
        );

        // Head of Dispatch (admin role - full access)
        User::updateOrCreate(
            ['email' => 'admin@nvg.movers'],
            [
                'name' => 'Head of Dispatch',
                'role' => 'admin',
                'password' => Hash::make('ChangeMe123!'),
            ]
        );

        // Dispatch team members (limited access)
        User::updateOrCreate(
            ['email' => 'dispatch@nvg.movers'],
            [
                'name' => 'Dispatch Officer 1',
                'role' => 'dispatch-user',
                'password' => Hash::make('ChangeMe123!'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'dispatch2@nvg.movers'],
            [
                'name' => 'Dispatch Officer 2',
                'role' => 'dispatch-user',
                'password' => Hash::make('ChangeMe123!'),
            ]
        );

        // Seed all data tables
        $this->call([
            \Database\Seeders\DriverSeeder::class,
            \Database\Seeders\VehicleSeeder::class,
            \Database\Seeders\ClientSeeder::class,
            \Database\Seeders\DeliveryRequestSeeder::class,
            \Database\Seeders\TripSeeder::class,
        ]);
    }
}
