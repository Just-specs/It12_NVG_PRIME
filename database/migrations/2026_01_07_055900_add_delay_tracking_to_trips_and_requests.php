<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update trips table
        Schema::table('trips', function (Blueprint $table) {
            // Modify status enum to include 'delayed'
            $table->enum('status', ['scheduled', 'in-transit', 'delayed', 'completed', 'cancelled', 'archived'])->default('scheduled')->change();
            
            // Add delay tracking fields
            $table->boolean('is_delayed')->default(false)->after('status');
            $table->timestamp('delay_detected_at')->nullable()->after('is_delayed');
            $table->text('delay_reason')->nullable()->after('delay_detected_at');
            $table->foreignId('delay_reason_by')->nullable()->constrained('users')->after('delay_reason');
            $table->integer('delay_minutes')->nullable()->after('delay_reason_by')->comment('Minutes delayed from scheduled time');
        });

        // Update delivery_requests table
        Schema::table('delivery_requests', function (Blueprint $table) {
            // Modify status enum to include 'delayed'
            $table->enum('status', ['pending', 'verified', 'assigned', 'in-transit', 'delayed', 'completed', 'cancelled', 'archived'])->default('pending')->change();
            
            // Add delay tracking fields
            $table->boolean('is_delayed')->default(false)->after('status');
            $table->timestamp('delay_detected_at')->nullable()->after('is_delayed');
            $table->text('delay_reason')->nullable()->after('delay_detected_at');
            $table->foreignId('delay_reason_by')->nullable()->constrained('users')->after('delay_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn(['is_delayed', 'delay_detected_at', 'delay_reason', 'delay_reason_by', 'delay_minutes']);
            $table->enum('status', ['scheduled', 'in-transit', 'completed', 'cancelled', 'archived'])->default('scheduled')->change();
        });

        Schema::table('delivery_requests', function (Blueprint $table) {
            $table->dropColumn(['is_delayed', 'delay_detected_at', 'delay_reason', 'delay_reason_by']);
            $table->enum('status', ['pending', 'verified', 'assigned', 'in-transit', 'completed', 'cancelled', 'archived'])->default('pending')->change();
        });
    }
};
