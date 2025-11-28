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
        $faker = \Faker\Factory::create();
        $types = ['Container Truck', 'Flatbed Truck', 'Refrigerated Truck', 'Tanker Truck', 'Box Truck'];
        $trailers = ['20ft Container', '40ft Container', 'Flatbed', '20ft Reefer', 'Liquid Tanker', 'Enclosed Box'];
        $statuses = ['available', 'in-use', 'maintenance'];
        for ($i = 0; $i < 36; $i++) {
            Vehicle::updateOrCreate([
                'plate_number' => $faker->unique()->regexify('TRUCK-[0-9]{3}')
            ], [
                'vehicle_type' => $faker->randomElement($types),
                'trailer_type' => $faker->randomElement($trailers),
                'status' => $faker->randomElement($statuses),
            ]);
        }
    }
}
