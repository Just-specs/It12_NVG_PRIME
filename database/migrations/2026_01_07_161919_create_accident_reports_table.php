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
        Schema::create('accident_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            
            $table->dateTime('accident_date');
            $table->string('location');
            $table->enum('severity', ['minor', 'moderate', 'severe', 'fatal'])->default('minor');
            $table->text('description');
            $table->text('injuries')->nullable();
            $table->text('vehicle_damage')->nullable();
            $table->text('other_party_info')->nullable();
            $table->boolean('police_report_filed')->default(false);
            $table->string('police_report_number')->nullable();
            $table->text('witness_info')->nullable();
            $table->text('action_taken')->nullable();
            $table->decimal('estimated_damage_cost', 10, 2)->nullable();
            $table->enum('status', ['pending', 'under_investigation', 'resolved', 'closed'])->default('pending');
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accident_reports');
    }
};
