<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccidentReport;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;

class AccidentReportSeeder extends Seeder
{
    public function run()
    {
        // Get some trips, drivers, vehicles
        $trips = Trip::with(['driver', 'vehicle'])->limit(15)->get();
        
        if ($trips->count() < 15) {
            $this->command->error('Not enough trips in database. Need at least 15 trips.');
            return;
        }

        $users = User::whereIn('role', ['dispatcher', 'head_dispatch', 'admin'])->get();
        if ($users->count() === 0) {
            $this->command->error('No users found to report accidents.');
            return;
        }

        $severities = ['minor', 'moderate', 'severe', 'fatal'];
        $statuses = ['pending', 'under_investigation', 'resolved', 'closed'];
        
        $locations = [
            'NLEX KM 25, Valenzuela',
            'SLEX Alabang Exit',
            'C5 Road, Taguig',
            'EDSA Shaw Boulevard',
            'MacArthur Highway, Bulacan',
            'Cavite Expressway KM 10',
            'Marcos Highway, Antipolo',
            'Quirino Highway, Novaliches',
            'South Luzon Expressway KM 50',
            'Commonwealth Avenue, QC',
            'NLEX Bocaue Exit',
            'Roxas Boulevard, Pasay',
            'Alabang-Zapote Road',
            'SCTEX Clark Exit',
            'Ortigas Avenue Extension'
        ];

        $descriptions = [
            'Vehicle encountered a mechanical failure causing the brakes to malfunction. Driver managed to pull over safely but rear-ended by another vehicle.',
            'While making a delivery, the truck was involved in a collision with a motorcycle. Motorcycle driver sustained minor injuries.',
            'Heavy rain caused poor visibility. Vehicle skidded and hit the guardrail on the highway.',
            'Another vehicle suddenly changed lanes without signaling, causing our truck to swerve and hit a parked car.',
            'Tire blowout on the expressway caused the vehicle to lose control momentarily. No other vehicles involved.',
            'T-bone collision at intersection. Traffic light was malfunctioning, causing confusion.',
            'Rear-ended by a sedan while stopped at a red light. Sedan driver was using mobile phone.',
            'Minor fender bender in parking area while maneuvering. No injuries reported.',
            'Side-swipe collision with another truck on narrow road. Both vehicles sustained paint damage.',
            'Vehicle hydroplaned during heavy rain, hit concrete barrier. Driver unharmed.',
            'Backed into a pole at loading dock. Limited visibility due to cargo placement.',
            'Struck by falling debris from construction site. Windshield damaged.',
            'Emergency stop to avoid pedestrian resulted in rear-end collision from following vehicle.',
            'Hit and run incident. Other vehicle fled the scene. Police report filed.',
            'Low-speed collision in traffic jam. Minor bumper damage only.'
        ];

        $injuries = [
            'Driver complained of neck pain, taken to hospital for examination. Released same day.',
            'Motorcycle rider sustained minor cuts and bruises. Treated at the scene.',
            null,
            'No injuries reported by any party.',
            'Driver had minor whiplash, advised to rest for 3 days.',
            'Passenger in other vehicle sustained facial lacerations from broken glass.',
            null,
            null,
            null,
            null,
            null,
            'Driver had minor bruising from seatbelt.',
            null,
            null,
            null
        ];

        $vehicleDamages = [
            'Front bumper damaged, radiator leak detected. Headlight broken.',
            'Front left fender dented, side mirror broken.',
            'Right side scratched along entire length. Guardrail impact damage.',
            'Left rear panel dented, taillight shattered.',
            'Front right tire blown out, wheel rim damaged.',
            'Front end extensively damaged, hood crumpled.',
            'Rear bumper crushed, trunk latch damaged.',
            'Rear bumper minor dent, paint scratched.',
            'Left side paint damage and mirror broken.',
            'Front bumper cracked, damage to front grille.',
            'Rear panel dented.',
            'Windshield cracked, hood dented.',
            'Rear bumper damaged.',
            'Left side damage, broken mirror and dented door.',
            'Front bumper minor scratches.'
        ];

        $actions = [
            'Vehicle towed to garage for repairs. Substitute vehicle assigned. Insurance claim filed.',
            'Police called to scene. Incident report filed. Motorcycle rider taken to hospital.',
            'Highway patrol assisted. Vehicle able to continue after inspection.',
            'Information exchanged with other driver. Photos taken of damage.',
            'Emergency tire change performed. Vehicle driven to nearest service center.',
            'Ambulance called for injured party. Police report filed at the scene.',
            'Insurance information exchanged. Photos documented damage.',
            'Supervisor notified. Minor repair scheduled.',
            'Both drivers exchanged information. Police report filed later.',
            'Vehicle inspected by highway patrol. Cleared to continue with caution.',
            'Dock supervisor notified. Repair scheduled for next day.',
            'Construction company contacted. Insurance claim to be filed.',
            'Police report filed. Dash cam footage preserved as evidence.',
            'Police called immediately. CCTV footage requested from nearby establishments.',
            'Information exchanged. No police report deemed necessary.'
        ];

        $this->command->info('Creating 15 accident reports...');

        foreach ($trips->take(15) as $index => $trip) {
            $accidentDate = Carbon::now()->subDays(rand(1, 60))->subHours(rand(0, 23));
            $severity = $severities[array_rand($severities)];
            $status = $statuses[array_rand($statuses)];
            $reportedBy = $users->random();

            $report = AccidentReport::create([
                'trip_id' => $trip->id,
                'driver_id' => $trip->driver_id,
                'vehicle_id' => $trip->vehicle_id,
                'reported_by' => $reportedBy->id,
                'accident_date' => $accidentDate,
                'location' => $locations[$index],
                'severity' => $severity,
                'description' => $descriptions[$index],
                'injuries' => $injuries[$index],
                'vehicle_damage' => $vehicleDamages[$index],
                'other_party_info' => rand(0, 1) ? 'Other party: ' . ['Honda Civic', 'Toyota Vios', 'Motorcycle', 'Isuzu Truck', 'Nissan Sentra'][rand(0, 4)] . ', Driver contacted and information exchanged.' : null,
                'police_report_filed' => rand(0, 1),
                'police_report_number' => rand(0, 1) ? 'PR-2025-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) : null,
                'witness_info' => rand(0, 1) ? 'Witness: ' . ['Juan Dela Cruz - 0917-123-4567', 'Maria Santos - 0918-234-5678', 'Security Guard on duty', 'Multiple witnesses at scene'][rand(0, 3)] : null,
                'action_taken' => $actions[$index],
                'estimated_damage_cost' => rand(5000, 150000),
                'status' => $status,
                'resolution_notes' => in_array($status, ['resolved', 'closed']) ? 'Repairs completed. Insurance claim approved. Driver cleared of liability.' : null,
                'resolved_at' => in_array($status, ['resolved', 'closed']) ? $accidentDate->copy()->addDays(rand(7, 30)) : null,
            ]);

            $this->command->info("Created accident report #{$report->id} for Trip #{$trip->id} - {$severity} severity");
        }

        $this->command->info("\n? Successfully created 15 accident reports!");
        $this->command->info("\nSummary:");
        $this->command->info("  Pending: " . AccidentReport::where('status', 'pending')->count());
        $this->command->info("  Under Investigation: " . AccidentReport::where('status', 'under_investigation')->count());
        $this->command->info("  Resolved: " . AccidentReport::where('status', 'resolved')->count());
        $this->command->info("  Closed: " . AccidentReport::where('status', 'closed')->count());
    }
}
