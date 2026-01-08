<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeliveryRequest;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Trip;
use Carbon\Carbon;

class RealisticDeliveryRequestSeeder extends Seeder
{
    private $shippingLines = ['WANHAI', 'CMA', 'COSCO', 'EVERGREEN', 'MCC', 'ONE', 'OOCL', 'SITC', 'MAERSK'];
    private $containerSizes = ['20ft', '40ft', '40ft HC'];
    private $containerTypes = ['standard', 'refrigerated', 'open_top', 'flat_rack'];
    private $contactMethods = ['mobile', 'email', 'group_chat'];
    
    private $pickupLocations = [
        'DICT', 'Kauswagan', 'Sasa Wharf', 'PHIVIDEC', 'Sta. Ana Wharf', 
        'Bredco Port', 'Lipata Port', 'PICOP Container Yard'
    ];
    
    private $deliveryLocations = [
        'Darong', 'Tubod', 'Cagayan de Oro', 'Bukidnon', 'Iligan', 
        'Malaybalay', 'Valencia', 'Maramag', 'Tagum', 'Panabo'
    ];
    
    private $shippers = [
        'AGRI EXIM GLOBAL', 'LI-ZHEN CORP.', 'SARAP FRUITS', 'DOLE PHILIPPINES',
        'UNIFRUTTI TROPICAL', 'SUMIFRU', 'TADECO', 'PLANET BANANAS',
        'AMS FARMING', 'LAPANDAY FOODS', 'DAVAO FRUITS', 'PACIFIC FOODS'
    ];
    
    private $containerStatuses = ['loaded', 'empty', 'return'];

    public function run()
    {
        $clients = Client::all();
        $drivers = Driver::all();
        $vehicles = Vehicle::all();
        
        if ($clients->isEmpty() || $drivers->isEmpty() || $vehicles->isEmpty()) {
            $this->command->error('Please seed clients, drivers, and vehicles first!');
            return;
        }

        $this->command->info('Creating 100 realistic delivery requests...');
        
        // Generate requests for the past 60 days and next 30 days
        $startDate = Carbon::now()->subDays(60);
        $endDate = Carbon::now()->addDays(30);
        
        for ($i = 1; $i <= 100; $i++) {
            // Random date between start and end
            $randomDate = Carbon::createFromTimestamp(
                rand($startDate->timestamp, $endDate->timestamp)
            );
            
            // Set random time during business hours (8 AM - 5 PM)
            $randomDate->setHour(rand(8, 17));
            $randomDate->setMinute(rand(0, 59));
            
            $client = $clients->random();
            $isPast = $randomDate->isPast();
            
            // Determine status based on date
            $status = $this->determineStatus($randomDate, $isPast);
            $deletedAt = null;
            
            // 10% chance of being soft deleted
            if (rand(1, 100) <= 10) {
                $deletedAt = Carbon::now()->subDays(rand(1, 30));
                $status = 'cancelled';
            }
            
            // Generate realistic ATW reference
            $atwRef = $this->generateAtwReference($i);
            
            $deliveryRequest = DeliveryRequest::create([
                'client_id' => $client->id,
                'contact_method' => $this->contactMethods[array_rand($this->contactMethods)],
                'atw_reference' => $atwRef,
                'eir_number' => $this->generateEirNumber(),
                'booking_number' => $this->generateBookingNumber(),
                'container_number' => $this->generateContainerNumber(),
                'seal_number' => $this->generateSealNumber(),
                'pickup_location' => $this->pickupLocations[array_rand($this->pickupLocations)],
                'delivery_location' => $this->deliveryLocations[array_rand($this->deliveryLocations)],
                'container_size' => $this->containerSizes[array_rand($this->containerSizes)],
                'container_type' => $this->containerTypes[array_rand($this->containerTypes)],
                'shipping_line' => $this->shippingLines[array_rand($this->shippingLines)],
                'shipper_name' => $this->shippers[array_rand($this->shippers)],
                'container_status' => $this->containerStatuses[array_rand($this->containerStatuses)],
                'eir_time' => $randomDate->format('H:i:s'),
                'preferred_schedule' => $randomDate,
                'notes' => $this->generateNotes(),
                'status' => $status,
                'atw_verified' => in_array($status, ['verified', 'assigned', 'completed']) ? true : (rand(1, 100) > 30),
                'deleted_at' => $deletedAt,
                'created_at' => $randomDate->copy()->subHours(rand(1, 48)),
            ]);
            
            // Create trip if status is assigned or completed
            if (in_array($status, ['assigned', 'completed']) && !$deletedAt) {
                $driver = $drivers->random();
                $vehicle = $vehicles->random();
                
                $tripStatus = $status === 'completed' ? 'completed' : 
                             ($isPast ? 'completed' : (rand(1, 100) > 50 ? 'in-transit' : 'scheduled'));
                
                Trip::create([
                    'delivery_request_id' => $deliveryRequest->id,
                    'driver_id' => $driver->id,
                    'vehicle_id' => $vehicle->id,
                    'waybill_number' => $this->generateWaybillNumber($i),
                    'scheduled_time' => $randomDate,
                    'actual_start_time' => in_array($tripStatus, ['in-transit', 'completed']) ? 
                        $randomDate->copy()->addMinutes(rand(0, 30)) : null,
                    'actual_end_time' => $tripStatus === 'completed' ? 
                        $randomDate->copy()->addHours(rand(3, 8)) : null,
                    'trip_rate' => rand(4500, 7000),
                    'additional_charge_20ft' => rand(0, 50) > 20 ? rand(20, 100) : 0,
                    'additional_charge_50' => rand(0, 50) > 30 ? rand(50, 150) : 0,
                    'driver_payroll' => rand(3500, 5500),
                    'driver_allowance' => rand(300, 800),
                    'status' => $tripStatus,
                    'created_at' => $deliveryRequest->created_at,
                ]);
            }
            
            if ($i % 10 === 0) {
                $this->command->info("Created {$i} requests...");
            }
        }
        
        $this->command->info('? Successfully created 100 realistic delivery requests!');
    }
    
    private function determineStatus($date, $isPast)
    {
        if ($isPast) {
            // Past dates: mostly completed, some assigned
            $rand = rand(1, 100);
            if ($rand <= 70) return 'completed';
            if ($rand <= 85) return 'assigned';
            if ($rand <= 95) return 'verified';
            return 'pending';
        } else {
            // Future dates: various statuses
            $rand = rand(1, 100);
            if ($rand <= 40) return 'pending';
            if ($rand <= 70) return 'verified';
            if ($rand <= 90) return 'assigned';
            return 'completed';
        }
    }
    
    private function generateAtwReference($num)
    {
        return str_pad($num + 1500, 4, '0', STR_PAD_LEFT);
    }
    
    private function generateWaybillNumber($num)
    {
        return str_pad($num + 1500, 4, '0', STR_PAD_LEFT);
    }
    
    private function generateEirNumber()
    {
        if (rand(1, 100) > 30) {
            return rand(700000, 800000);
        }
        return null;
    }
    
    private function generateBookingNumber()
    {
        if (rand(1, 100) > 40) {
            $prefix = ['GHC', 'MCC', 'WHL', 'CMA', 'EVG'][array_rand(['GHC', 'MCC', 'WHL', 'CMA', 'EVG'])];
            return $prefix . rand(100000, 999999) . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        }
        return null;
    }
    
    private function generateContainerNumber()
    {
        if (rand(1, 100) > 20) {
            $prefix = ['WHSU', 'OTPU', 'SEGU', 'CMAU', 'EITU'][array_rand(['WHSU', 'OTPU', 'SEGU', 'CMAU', 'EITU'])];
            return $prefix . ' ' . rand(100000, 999999) . '-' . rand(0, 9);
        }
        return null;
    }
    
    private function generateSealNumber()
    {
        if (rand(1, 100) > 50) {
            return 'R' . rand(1000000, 9999999);
        }
        return null;
    }
    
    private function generateNotes()
    {
        $notes = [
            'Urgent delivery required',
            'Handle with care - fragile items',
            'Contact client upon arrival',
            'Requires cold storage',
            'Weekend delivery acceptable',
            'Gate closes at 4 PM',
            'Driver must have valid ID',
            null, null, null // 30% chance of no notes
        ];
        
        return $notes[array_rand($notes)];
    }
}