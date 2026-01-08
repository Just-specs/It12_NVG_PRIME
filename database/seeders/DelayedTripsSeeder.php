<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;

class DelayedTripsSeeder extends Seeder
{
    public function run()
    {
        // Get 15 scheduled or in-transit trips
        $trips = Trip::whereIn('status', ['scheduled', 'in-transit'])
            ->with(['driver', 'vehicle', 'deliveryRequest.client'])
            ->limit(15)
            ->get();
        
        if ($trips->count() < 15) {
            $this->command->warn("Only {$trips->count()} trips available to mark as delayed.");
        }

        $delayReasons = [
            'Heavy traffic congestion on NLEX due to road construction. Expected delay of 2-3 hours.',
            'Vehicle breakdown - engine overheating. Mechanic called to location. Estimated repair time: 1 hour.',
            'Accident on expressway caused major traffic jam. All lanes blocked for 45 minutes.',
            'Delayed at client pickup location. Client was not ready with cargo. Waited 90 minutes.',
            'Flat tire encountered en route. Spare tire replacement took 30 minutes.',
            'Heavy rainfall and flooding in delivery area. Decided to wait for weather to clear for safety.',
            'Driver got lost due to incorrect GPS directions. Took 40 minutes to find correct route.',
            'Checkpoint inspection took longer than expected. Delayed by 1 hour at checkpoint.',
            'Unexpected road closure due to government event. Had to take alternative route adding 2 hours.',
            'Vehicle encountered mechanical issues with transmission. Temporary repair performed on-site.',
            'Client rescheduled delivery time at last minute. Had to wait at holding area.',
            'Driver fell ill during trip. Had to rest and seek medical attention. Delay of 2 hours.',
            'Cargo needed additional securing after initial inspection. Safety procedure took 45 minutes.',
            'Port congestion - long queue at container yard. Waiting time exceeded 3 hours.',
            'Traffic accident ahead blocked all lanes. Standstill for 90 minutes before clearance.'
        ];

        $users = User::whereIn('role', ['dispatcher', 'head_dispatch', 'admin'])->get();
        
        if ($users->count() === 0) {
            $this->command->error('No dispatcher users found.');
            return;
        }

        $this->command->info("Marking {$trips->count()} trips as delayed...\n");

        foreach ($trips as $index => $trip) {
            $now = Carbon::now();
            $scheduledTime = $trip->scheduled_time;
            
            // Calculate realistic delay (between 30 minutes to 4 hours)
            $delayMinutes = rand(30, 240);
            
            // Set delay detected time as scheduled time + some minutes
            $delayDetectedAt = $scheduledTime->copy()->addMinutes(20);
            
            // Random user or system
            $reportedBy = rand(0, 1) ? $users->random()->id : null;
            $reportedByName = $reportedBy ? User::find($reportedBy)->name : 'System (Automatic Detection)';

            $trip->update([
                'status' => 'delayed',
                'is_delayed' => true,
                'delay_detected_at' => $delayDetectedAt,
                'delay_minutes' => $delayMinutes,
                'delay_reason' => $delayReasons[$index % count($delayReasons)],
                'delay_reason_by' => $reportedBy,
            ]);

            // Also update the delivery request status
            if ($trip->deliveryRequest) {
                $trip->deliveryRequest->update([
                    'status' => 'assigned', // Keep as assigned, not delayed
                ]);
            }

            $this->command->info("? Trip #{$trip->id} - {$delayMinutes} min delay - Reported by: {$reportedByName}");
        }

        $this->command->info("\n? Successfully marked {$trips->count()} trips as delayed!");
        $this->command->info("\nYou can now test the delay reason feature!");
    }
}
