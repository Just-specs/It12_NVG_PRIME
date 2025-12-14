<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify status enum for delivery_requests to include 'archived'
        DB::statement("ALTER TABLE delivery_requests MODIFY COLUMN status ENUM('pending', 'verified', 'assigned', 'in-transit', 'completed', 'cancelled', 'archived') DEFAULT 'pending'");

        // Modify status enum for trips to include 'archived'
        DB::statement("ALTER TABLE trips MODIFY COLUMN status ENUM('scheduled', 'in-transit', 'completed', 'cancelled', 'archived') DEFAULT 'scheduled'");

        // Add archived_at column to delivery_requests if not exists
        if (!Schema::hasColumn('delivery_requests', 'archived_at')) {
            Schema::table('delivery_requests', function (Blueprint $table) {
                $table->timestamp('archived_at')->nullable()->after('updated_at');
            });
        }

        // Add archived_at column to trips if not exists
        if (!Schema::hasColumn('trips', 'archived_at')) {
            Schema::table('trips', function (Blueprint $table) {
                $table->timestamp('archived_at')->nullable()->after('updated_at');
            });
        }

        // Archive old requests that are 7+ days past due and not completed/cancelled
        $sevenDaysAgo = Carbon::now()->subDays(7);
        DB::table('delivery_requests')
            ->where('preferred_schedule', '<', $sevenDaysAgo)
            ->whereIn('status', ['pending', 'verified', 'assigned'])
            ->whereNull('archived_at')
            ->update([
                'archived_at' => Carbon::now(),
                'status' => 'archived'
            ]);

        // Archive old trips that are 7+ days past due and not completed/cancelled
        DB::table('trips')
            ->where('scheduled_time', '<', $sevenDaysAgo)
            ->where('status', 'scheduled')
            ->whereNull('archived_at')
            ->update([
                'archived_at' => Carbon::now(),
                'status' => 'archived'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('delivery_requests', 'archived_at')) {
            Schema::table('delivery_requests', function (Blueprint $table) {
                $table->dropColumn('archived_at');
            });
        }

        if (Schema::hasColumn('trips', 'archived_at')) {
            Schema::table('trips', function (Blueprint $table) {
                $table->dropColumn('archived_at');
            });
        }

        // Revert status enum for delivery_requests
        DB::statement("ALTER TABLE delivery_requests MODIFY COLUMN status ENUM('pending', 'verified', 'assigned', 'in-transit', 'completed', 'cancelled') DEFAULT 'pending'");

        // Revert status enum for trips
        DB::statement("ALTER TABLE trips MODIFY COLUMN status ENUM('scheduled', 'in-transit', 'completed', 'cancelled') DEFAULT 'scheduled'");
    }
};
