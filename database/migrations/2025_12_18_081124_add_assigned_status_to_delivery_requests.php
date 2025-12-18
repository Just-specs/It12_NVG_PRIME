<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'assigned' status to delivery_requests status enum
        DB::statement("ALTER TABLE delivery_requests MODIFY COLUMN status ENUM('pending', 'confirmed', 'verified', 'assigned', 'in-transit', 'delivered', 'completed', 'cancelled', 'archived') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'assigned' status from delivery_requests status enum
        DB::statement("ALTER TABLE delivery_requests MODIFY COLUMN status ENUM('pending', 'confirmed', 'verified', 'in-transit', 'delivered', 'completed', 'cancelled', 'archived') DEFAULT 'pending'");
    }
};
