<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Alter the status column to include 'archived' as a valid value
        DB::statement("ALTER TABLE delivery_requests MODIFY COLUMN status ENUM('pending', 'confirmed', 'in-transit', 'delivered', 'completed', 'cancelled', 'archived') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Revert back to previous enum values
        DB::statement("ALTER TABLE delivery_requests MODIFY COLUMN status ENUM('pending', 'confirmed', 'in-transit', 'delivered', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};
