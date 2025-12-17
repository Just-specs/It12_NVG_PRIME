<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Alter the role column to include 'admin' as a valid value
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('head_dispatch', 'dispatch', 'user', 'admin') NOT NULL DEFAULT 'dispatch'");
    }

    public function down(): void
    {
        // Revert back to previous enum values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('head_dispatch', 'dispatch', 'user') NOT NULL DEFAULT 'dispatch'");
    }
};
