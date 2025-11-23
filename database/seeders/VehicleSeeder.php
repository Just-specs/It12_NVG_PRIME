<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            [
                'plate_number' => 'TRUCK-001',
                'vehicle_type' => 'Container Truck',
                'trailer_type' => '20ft Container',
                'status' => 'available',
            ],
            [
                'plate_number' => 'TRUCK-002',
                'vehicle_type' => 'Container Truck',
                'trailer_type' => '40ft Container',
                'status' => 'available',
            ],
            [
                'plate_number' => 'TRUCK-003',
                'vehicle_type' => 'Flatbed Truck',
                'trailer_type' => 'Flatbed',
                'status' => 'in-use',
            ],
            [
                'plate_number' => 'TRUCK-004',
                'vehicle_type' => 'Container Truck',
                'trailer_type' => '20ft Container',
                'status' => 'available',
            ],
            [
                'plate_number' => 'TRUCK-005',
                'vehicle_type' => 'Refrigerated Truck',
                'trailer_type' => '20ft Reefer',
                'status' => 'maintenance',
            ],
            [
                'plate_number' => 'TRUCK-006',
                'vehicle_type' => 'Container Truck',
                'trailer_type' => '40ft Container',
                'status' => 'available',
            ],
            [
                'plate_number' => 'TRUCK-007',
                'vehicle_type' => 'Tanker Truck',
                'trailer_type' => 'Liquid Tanker',
                'status' => 'in-use',
            ],
            [
                'plate_number' => 'TRUCK-008',
                'vehicle_type' => 'Container Truck',
                'trailer_type' => '20ft Container',
                'status' => 'available',
            ],
            [
                'plate_number' => 'TRUCK-009',
                'vehicle_type' => 'Flatbed Truck',
                'trailer_type' => 'Flatbed',
                'status' => 'available',
            ],
            [
                'plate_number' => 'TRUCK-010',
                'vehicle_type' => 'Container Truck',
                'trailer_type' => '40ft Container',
                'status' => 'maintenance',
            ],
            [
                'plate_number' => 'TRUCK-011',
                'vehicle_type' => 'Box Truck',
                'trailer_type' => 'Enclosed Box',
                'status' => 'available',
            ],
            [
                'plate_number' => 'TRUCK-012',
                'vehicle_type' => 'Container Truck',
                'trailer_type' => '20ft Container',
                'status' => 'available',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::create($vehicle);
        }
    }
}
