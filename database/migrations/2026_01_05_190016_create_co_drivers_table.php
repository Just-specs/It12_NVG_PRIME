<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Check if partner_id column exists and drop it safely
        if (Schema::hasColumn('drivers', 'partner_id')) {
            // Try to drop foreign key if it exists
            try {
                Schema::table('drivers', function (Blueprint $table) {
                    $table->dropForeign(['partner_id']);
                });
            } catch (\Exception $e) {
                // Foreign key doesn't exist, continue
            }
            
            // Drop the partner_id column
            Schema::table('drivers', function (Blueprint $table) {
                $table->dropColumn('partner_id');
            });
        }
        
        // Create co_drivers pivot table if it doesn't exist
        if (!Schema::hasTable('co_drivers')) {
            Schema::create('co_drivers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');
                $table->foreignId('co_driver_id')->constrained('drivers')->onDelete('cascade');
                $table->timestamps();
                
                // Ensure unique pairs
                $table->unique(['driver_id', 'co_driver_id']);
            });
        }
    }

    public function down()
    {
        // Drop co_drivers table
        Schema::dropIfExists('co_drivers');
        
        // Restore partner_id column if it doesn't exist
        if (!Schema::hasColumn('drivers', 'partner_id')) {
            Schema::table('drivers', function (Blueprint $table) {
                $table->foreignId('partner_id')->nullable()->after('license_number');
            });
            
            // Restore foreign key
            Schema::table('drivers', function (Blueprint $table) {
                $table->foreign('partner_id')->references('id')->on('drivers')->onDelete('set null');
            });
        }
    }
};
