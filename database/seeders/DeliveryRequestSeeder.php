<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\DeliveryRequest;
use Illuminate\Database\Seeder;

class DeliveryRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::all();

        if ($clients->isEmpty()) {
            $clients = Client::factory()->count(10)->create();
        }

        // For each client, create 6-12 realistic delivery requests (increase dataset)
        foreach ($clients as $client) {
            DeliveryRequest::factory()
                ->count(rand(6, 12))
                ->for($client)
                ->create();
        }

        // Add some high-priority realistic requests
        $firstClient = Client::first();
        if ($firstClient) {
            // Add a few priority realistic requests
            DeliveryRequest::factory()->create([
                'client_id' => $firstClient->id,
                'atw_reference' => 'ATW-URG001',
                'pickup_location' => 'Warehouse A, Port City',
                'delivery_location' => 'Retail Hub, Downtown',
                'status' => 'pending',
                'preferred_schedule' => now()->addDay(),
                'notes' => 'Priority delivery, handle with care',
                'atw_verified' => false,
            ]);

            DeliveryRequest::factory()->create([
                'client_id' => $firstClient->id,
                'atw_reference' => 'ATW-EXP002',
                'pickup_location' => 'Cold Storage, Port City',
                'delivery_location' => 'Supermarket Chain Warehouse',
                'status' => 'assigned',
                'preferred_schedule' => now()->addHours(6),
                'notes' => 'Reefer container required. Temperature 2-4°C',
                'atw_verified' => true,
            ]);

            DeliveryRequest::factory()->create([
                'client_id' => $firstClient->id,
                'atw_reference' => 'ATW-PRIO003',
                'pickup_location' => 'Express Depot, North Pier',
                'delivery_location' => 'Hospital Supplies Center',
                'status' => 'verified',
                'preferred_schedule' => now()->addHours(12),
                'notes' => 'Medical supplies - urgent',
                'atw_verified' => true,
            ]);
        }
    }
}
