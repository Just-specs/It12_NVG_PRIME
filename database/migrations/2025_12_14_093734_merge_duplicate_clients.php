<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Define duplicates to merge: [keep_id => delete_id]
        $duplicatePairs = [
            2 => 3,  // Keep "AGRI EXIM" (ID: 2), remove "AGRIEXIM" (ID: 3)
            4 => 5,  // Keep "F2 LIZHEN" (ID: 4), remove "F2 LI-ZHEN" (ID: 5)
            9 => 10, // Keep "GOOD FARMER CAGANGOHAN" (ID: 9), remove "GOOD FARMERS CAGANGOHAN" (ID: 10)
        ];

        foreach ($duplicatePairs as $keepId => $deleteId) {
            // Update all delivery requests to point to the kept client
            DB::table('delivery_requests')
                ->where('client_id', $deleteId)
                ->update(['client_id' => $keepId]);

            // Update any client_notifications if they exist
            DB::table('client_notifications')
                ->where('client_id', $deleteId)
                ->update(['client_id' => $keepId]);

            // Delete the duplicate client
            DB::table('clients')->where('id', $deleteId)->delete();

            echo "Merged client ID {$deleteId} into {$keepId}\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse this migration as we've deleted data
        echo "Cannot reverse client merge migration\n";
    }
};
