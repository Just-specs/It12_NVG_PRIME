<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Vehicle;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Create test clients
        $clients = [
            ['name' => 'ABC Corporation', 'email' => 'abc@example.com', 'mobile' => '09171234567', 'company' => 'ABC Corp'],
            ['name' => 'XYZ Trading', 'email' => 'xyz@example.com', 'mobile' => '09187654321', 'company' => 'XYZ Trading Co'],
            ['name' => 'Test Logistics', 'email' => 'test@example.com', 'mobile' => '09191112222', 'company' => 'Test Logistics Inc'],
        ];

        foreach ($clients as $client) {
            Client::firstOrCreate(['email' => $client['email']], $client);
        }

        // Create test drivers (all available)
        $drivers = [
            ['name' => 'John Doe', 'mobile' => '09171111111', 'license_number' => 'DL-001-2024', 'status' => 'available'],
            ['name' => 'Jane Smith', 'mobile' => '09172222222', 'license_number' => 'DL-002-2024', 'status' => 'available'],
            ['name' => 'Mike Johnson', 'mobile' => '09173333333', 'license_number' => 'DL-003-2024', 'status' => 'available'],
            ['name' => 'Sarah Williams', 'mobile' => '09174444444', 'license_number' => 'DL-004-2024', 'status' => 'available'],
        ];

        foreach ($drivers as $driver) {
            Driver::firstOrCreate(['license_number' => $driver['license_number']], $driver);
        }

        // Create test vehicles (all available)
        $vehicles = [
            ['plate_number' => 'ABC-1234', 'vehicle_type' => 'Prime Mover', 'trailer_type' => '40ft', 'status' => 'available'],
            ['plate_number' => 'XYZ-5678', 'vehicle_type' => 'Prime Mover', 'trailer_type' => '20ft', 'status' => 'available'],
            ['plate_number' => 'DEF-9012', 'vehicle_type' => 'Prime Mover', 'trailer_type' => '40ft', 'status' => 'available'],
            ['plate_number' => 'GHI-3456', 'vehicle_type' => 'Prime Mover', 'trailer_type' => '20ft', 'status' => 'available'],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::firstOrCreate(['plate_number' => $vehicle['plate_number']], $vehicle);
        }

        $this->command->info('Test data seeded successfully!');
        $this->command->info('- ' . count($clients) . ' clients created');
        $this->command->info('- ' . count($drivers) . ' drivers created (all available)');
        $this->command->info('- ' . count($vehicles) . ' vehicles created (all available)');
    }
}
