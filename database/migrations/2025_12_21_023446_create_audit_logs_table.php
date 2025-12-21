<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('user_name')->nullable(); // Store name in case user is deleted
            $table->string('action'); // created, updated, deleted, viewed, assigned, etc.
            $table->string('model_type'); // DeliveryRequest, Trip, Driver, etc.
            $table->unsignedBigInteger('model_id');
            $table->text('description'); // Human-readable description
            $table->json('old_values')->nullable(); // Before change
            $table->json('new_values')->nullable(); // After change
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['model_type', 'model_id']);
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};