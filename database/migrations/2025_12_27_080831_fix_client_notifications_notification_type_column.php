<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'scheduled' to the notification_type enum
        DB::statement("ALTER TABLE client_notifications MODIFY COLUMN notification_type ENUM('assignment', 'scheduled', 'in-transit', 'delay', 'completed', 'cancelled') NOT NULL");
    }

    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE client_notifications MODIFY COLUMN notification_type ENUM('assignment', 'in-transit', 'delay', 'completed') NOT NULL");
    }
};
