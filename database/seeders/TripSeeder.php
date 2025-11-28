<?php

namespace Database\Seeders;

use App\Models\Trip;
use App\Models\DeliveryRequest;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deliveryRequests = DeliveryRequest::all();
        $drivers = Driver::all();
        $vehicles = Vehicle::all();

        if ($deliveryRequests->isEmpty() || $drivers->isEmpty() || $vehicles->isEmpty()) {
            return; // Skip if dependencies aren't seeded
        }

        $statuses = ['scheduled', 'in-transit', 'completed', 'cancelled'];

        foreach ($deliveryRequests->take(20) as $deliveryRequest) {
            Trip::updateOrCreate([
                'delivery_request_id' => $deliveryRequest->id,
            ], [
                'driver_id' => $drivers->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'scheduled_time' => now()->addDays(rand(1, 7)),
                'actual_start_time' => rand(0, 1) ? now()->subDays(rand(1, 5)) : null,
                'actual_end_time' => rand(0, 1) ? now()->subDays(rand(1, 5)) : null,
                'status' => collect($statuses)->random(),
                'route_instructions' => 'Follow main highway. Check vehicle before departure.',
            ]);
        }
    }
}
