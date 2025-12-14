<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DeliveryRequest;
use App\Models\Trip;
use Carbon\Carbon;

class ArchiveOverdueRequestsAndTrips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive delivery requests and trips that are 7 days past their scheduled date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to archive overdue requests and trips (7 days past due)...');

        // Calculate the date 7 days ago
        $sevenDaysAgo = Carbon::now()->subDays(7);

        // Archive overdue delivery requests that are 7+ days past their schedule
        $overdueRequests = DeliveryRequest::where('preferred_schedule', '<', $sevenDaysAgo)
            ->whereIn('status', ['pending', 'verified', 'assigned'])
            ->whereNull('archived_at')
            ->get();

        foreach ($overdueRequests as $request) {
            $request->update([
                'status' => 'archived',
                'archived_at' => Carbon::now()
            ]);
        }

        $this->info("Archived {$overdueRequests->count()} delivery requests (7+ days overdue).");

        // Archive overdue trips that are 7+ days past their schedule
        $overdueTrips = Trip::where('scheduled_time', '<', $sevenDaysAgo)
            ->whereIn('status', ['scheduled'])
            ->whereNull('archived_at')
            ->get();

        foreach ($overdueTrips as $trip) {
            $trip->update([
                'status' => 'archived',
                'archived_at' => Carbon::now()
            ]);

            // Also update the associated delivery request if not already archived
            if ($trip->deliveryRequest && !$trip->deliveryRequest->archived_at) {
                $trip->deliveryRequest->update([
                    'status' => 'archived',
                    'archived_at' => Carbon::now()
                ]);
            }
        }

        $this->info("Archived {$overdueTrips->count()} trips (7+ days overdue).");
        $this->info('Archiving completed successfully!');
        $this->info("Note: Records are archived only after being 7 days past their scheduled date.");

        return 0;
    }
}
