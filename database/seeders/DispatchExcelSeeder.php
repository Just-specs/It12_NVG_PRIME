<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\DeliveryRequest;
use App\Models\Trip;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class DispatchExcelSeeder extends Seeder
{
    public function run(): void
    {
        $excelFile = base_path('2025-DAILY-DISPATCH.xlsx');
        
        if (!file_exists($excelFile)) {
            $this->command->error("Excel file not found: $excelFile");
            return;
        }

        $this->command->info('Loading Excel file...');
        $spreadsheet = IOFactory::load($excelFile);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();

        $this->command->info("Found {$highestRow} rows");

        // Get all clients, drivers, and vehicles
        $clients = Client::all()->keyBy('name');
        $drivers = Driver::where('status', 'available')->get();
        $vehicles = Vehicle::where('status', 'available')->get();

        $driverIndex = 0;
        $vehicleIndex = 0;
        $currentDate = null;
        $importCount = 0;

        // Start from row 4 (after headers)
        for ($row = 4; $row <= $highestRow; $row++) {
            $cellA = trim($worksheet->getCell("A{$row}")->getValue() ?? '');
            $cellB = trim($worksheet->getCell("B{$row}")->getValue() ?? '');
            
            // Check if this is a date row
            if (preg_match('/^(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday)/', $cellB)) {
                // Extract date from the row
                $dateString = $cellB;
                try {
                    $currentDate = Carbon::parse($dateString);
                    $this->command->info("Processing date: {$currentDate->format('Y-m-d')}");
                } catch (\Exception $e) {
                    $this->command->warn("Could not parse date: $dateString");
                }
                continue;
            }

            // Skip section headers (LOR-JUNA-EPOY, etc.)
            if (stripos($cellB, 'SHIFT') !== false || empty($cellB)) {
                continue;
            }

            // Skip if no current date set
            if (!$currentDate) {
                continue;
            }

            // Get row data
            $clientName = trim($worksheet->getCell("B{$row}")->getValue() ?? '');
            $waybill = trim($worksheet->getCell("C{$row}")->getValue() ?? '');
            $status = trim($worksheet->getCell("D{$row}")->getValue() ?? '');
            $eirTime = trim($worksheet->getCell("E{$row}")->getValue() ?? '');
            $shippingLine = trim($worksheet->getCell("F{$row}")->getValue() ?? '');
            $from = trim($worksheet->getCell("G{$row}")->getValue() ?? '');
            $to = trim($worksheet->getCell("H{$row}")->getValue() ?? '');
            $shipper = trim($worksheet->getCell("I{$row}")->getValue() ?? '');
            $containerNo = trim($worksheet->getCell("L{$row}")->getValue() ?? '');

            // Skip if essential data is missing
            if (empty($clientName) || empty($waybill)) {
                continue;
            }

            // Find or create client
            $client = $clients->get($clientName);
            if (!$client) {
                $client = Client::create([
                    'name' => $clientName,
                    'email' => strtolower(str_replace(' ', '.', $clientName)) . '@example.com',
                    'phone' => '09' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT),
                    'address' => 'Davao City',
                    'contact_method' => 'mobile',
                    'status' => 'active'
                ]);
                $clients->put($clientName, $client);
                $this->command->info("Created client: $clientName");
            }

            // Parse time
            $scheduledTime = $currentDate->copy();
            if ($eirTime) {
                try {
                    $timeParts = $this->parseTime($eirTime);
                    $scheduledTime->setTime($timeParts['hour'], $timeParts['minute']);
                } catch (\Exception $e) {
                    $scheduledTime->setTime(8, 0); // Default 8 AM
                }
            }

            // Determine trip status from Excel status
            $tripStatus = 'completed'; // Default since most are SERVED
            if (stripos($status, 'CANCELLED') !== false || stripos($status, 'CANCEL') !== false) {
                $tripStatus = 'cancelled';
            } elseif (stripos($status, 'PENDING') !== false) {
                $tripStatus = 'scheduled';
            }

            // Create delivery request
            $deliveryRequest = DeliveryRequest::create([
                'client_id' => $client->id,
                'atw_reference' => $waybill,
                'pickup_location' => $from ?: 'DICT',
                'delivery_location' => $to ?: 'Unknown',
                'scheduled_date' => $scheduledTime->format('Y-m-d'),
                'scheduled_time' => $scheduledTime->format('H:i:s'),
                'cargo_details' => $shippingLine . ' - ' . $shipper,
                'special_instructions' => 'Container: ' . $containerNo,
                'contact_method' => 'mobile',
                'status' => $tripStatus === 'completed' ? 'completed' : 'pending',
                'priority' => 'normal'
            ]);

            // Assign driver and vehicle (round-robin)
            $driver = $drivers[$driverIndex % $drivers->count()];
            $vehicle = $vehicles[$vehicleIndex % $vehicles->count()];
            $driverIndex++;
            $vehicleIndex++;

            // Create trip
            $trip = Trip::create([
                'delivery_request_id' => $deliveryRequest->id,
                'driver_id' => $driver->id,
                'vehicle_id' => $vehicle->id,
                'scheduled_time' => $scheduledTime,
                'status' => $tripStatus,
                'actual_start_time' => $tripStatus === 'completed' ? $scheduledTime->copy() : null,
                'actual_end_time' => $tripStatus === 'completed' ? $scheduledTime->copy()->addHours(2) : null,
            ]);

            $importCount++;
            
            if ($importCount % 50 === 0) {
                $this->command->info("Imported $importCount trips...");
            }
        }

        $this->command->info("✅ Import complete! Imported $importCount trips from Excel.");
    }

    private function parseTime(string $timeStr): array
    {
        // Handle formats like: "10:15 AM", "15:50PM", "6:05 PM"
        $timeStr = strtoupper(trim($timeStr));
        $timeStr = str_replace(' ', '', $timeStr);
        
        // Extract hour, minute, and AM/PM
        if (preg_match('/(\d{1,2}):?(\d{2})?\s*(AM|PM)?/', $timeStr, $matches)) {
            $hour = (int)$matches[1];
            $minute = isset($matches[2]) ? (int)$matches[2] : 0;
            $period = $matches[3] ?? '';

            // Convert to 24-hour format
            if ($period === 'PM' && $hour < 12) {
                $hour += 12;
            } elseif ($period === 'AM' && $hour === 12) {
                $hour = 0;
            }

            return ['hour' => $hour, 'minute' => $minute];
        }

        return ['hour' => 8, 'minute' => 0]; // Default
    }
}
