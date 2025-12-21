<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'verified' to status ENUM
        DB::statement("ALTER TABLE delivery_requests MODIFY COLUMN status ENUM('pending', 'confirmed', 'verified', 'in-transit', 'delivered', 'completed', 'cancelled', 'archived') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Remove 'verified' from status ENUM
        DB::statement("ALTER TABLE delivery_requests MODIFY COLUMN status ENUM('pending', 'confirmed', 'in-transit', 'delivered', 'completed', 'cancelled', 'archived') NOT NULL DEFAULT 'pending'");
    }
};
