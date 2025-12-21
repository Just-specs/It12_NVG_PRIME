<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Alter the role column to include 'user' as a valid value
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('head_dispatch', 'dispatch', 'user') NOT NULL DEFAULT 'dispatch'");
    }

    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('head_dispatch', 'dispatch') NOT NULL DEFAULT 'dispatch'");
    }
};
