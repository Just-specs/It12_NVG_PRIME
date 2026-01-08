<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class SeedTestData extends Command
{
    protected $signature = 'test:seed {--drivers=17 : Number of drivers to create} {--vehicles=20 : Number of vehicles to create}';
    protected $description = 'Create random test drivers and vehicles';

    private $firstNames = ['John', 'Michael', 'David', 'James', 'Robert', 'William', 'Richard', 'Thomas', 'Charles', 'Daniel', 'Matthew', 'Anthony', 'Mark', 'Donald', 'Steven', 'Paul', 'Andrew', 'Joshua', 'Kenneth', 'Kevin'];
    
    private $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin'];
    
    private $vehicleMakes = ['Isuzu', 'Hino', 'Mitsubishi', 'Nissan', 'Toyota', 'Fuso', 'UD Trucks'];
    
    private $vehicleTypes = ['prime-mover', 'wing-van', '10-wheeler', '6-wheeler'];
    
    private $trailerTypes = ['40ft', '20ft', 'flatbed', 'lowbed'];

    public function handle()
    {
        $driverCount = $this->option('drivers');
        $vehicleCount = $this->option('vehicles');
        
        $this->info("Creating $driverCount random drivers...");
        $drivers = $this->createDrivers($driverCount);
        
        $this->info("Creating $vehicleCount random vehicles...");
        $vehicles = $this->createVehicles($vehicleCount);
        
        $this->info("\n? Test data created successfully!");
        $this->table(
            ['Type', 'Count', 'IDs'],
            [
                ['Drivers', count($drivers), implode(', ', array_slice($drivers, 0, 10)) . (count($drivers) > 10 ? '...' : '')],
                ['Vehicles', count($vehicles), implode(', ', array_slice($vehicles, 0, 10)) . (count($vehicles) > 10 ? '...' : '')]
            ]
        );
        
        return 0;
    }
    
    private function createDrivers(int $count): array
    {
        $createdIds = [];
        
        for ($i = 0; $i < $count; $i++) {
            $firstName = $this->firstNames[array_rand($this->firstNames)];
            $lastName = $this->lastNames[array_rand($this->lastNames)];
            $name = strtoupper($firstName . ' ' . $lastName);
            
            // Check if name exists
            $exists = Driver::whereRaw('UPPER(name) = ?', [$name])->exists();
            if ($exists) {
                $name .= ' ' . rand(1, 99);
            }
            
            $license = 'N' . rand(10, 99) . '-' . rand(10000000, 99999999);
            
            // Ensure unique license
            while (Driver::where('license_number', $license)->exists()) {
                $license = 'N' . rand(10, 99) . '-' . rand(10000000, 99999999);
            }
            
            $mobile = '09' . rand(100000000, 999999999);
            
            $driver = Driver::create([
                'name' => $name,
                'mobile' => $mobile,
                'license_number' => $license,
                'status' => ['available', 'on-trip', 'off-duty'][rand(0, 2)]
            ]);
            
            $createdIds[] = $driver->id;
            $this->line("  Created driver: $name (ID: {$driver->id})");
        }
        
        return $createdIds;
    }
    
    private function createVehicles(int $count): array
    {
        $createdIds = [];
        
        for ($i = 0; $i < $count; $i++) {
            $make = $this->vehicleMakes[array_rand($this->vehicleMakes)];
            $model = $make . ' ' . rand(2015, 2024);
            
            // Generate unique plate number
            do {
                $plate = strtoupper(chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90))) . '-' . rand(1000, 9999);
            } while (Vehicle::where('plate_number', $plate)->exists());
            
            $vehicleType = $this->vehicleTypes[array_rand($this->vehicleTypes)];
            $trailerType = $this->trailerTypes[array_rand($this->trailerTypes)];
            
            $vehicle = Vehicle::create([
                'plate_number' => $plate,
                'model' => $model,
                'vehicle_type' => $vehicleType,
                'trailer_type' => $trailerType,
                'status' => ['available', 'in-use', 'maintenance'][rand(0, 2)]
            ]);
            
            $createdIds[] = $vehicle->id;
            $this->line("  Created vehicle: $plate - $model (ID: {$vehicle->id})");
        }
        
        return $createdIds;
    }
}
