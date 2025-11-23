<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Clients table
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('company')->nullable();
            $table->timestamps();
        });

        // Drivers table
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mobile');
            $table->string('license_number');
            $table->enum('status', ['available', 'on-trip', 'off-duty'])->default('available');
            $table->timestamps();
        });

        // Vehicles table
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->unique();
            $table->string('vehicle_type'); // prime mover
            $table->string('trailer_type')->nullable(); // 20ft, 40ft, etc
            $table->enum('status', ['available', 'in-use', 'maintenance'])->default('available');
            $table->timestamps();
        });

        // Delivery Requests table
        Schema::create('delivery_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->enum('contact_method', ['mobile', 'email', 'group_chat']);
            $table->string('atw_reference');
            $table->string('pickup_location');
            $table->string('delivery_location');
            $table->string('container_size'); // 20ft, 40ft
            $table->string('container_type'); // standard, refrigerated, etc
            $table->dateTime('preferred_schedule');
            $table->enum('status', ['pending', 'verified', 'assigned', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->boolean('atw_verified')->default(false);
            $table->timestamps();
        });

        // Trips table
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->dateTime('scheduled_time');
            $table->dateTime('actual_start_time')->nullable();
            $table->dateTime('actual_end_time')->nullable();
            $table->enum('status', ['scheduled', 'in-transit', 'completed', 'cancelled'])->default('scheduled');
            $table->text('route_instructions')->nullable();
            $table->timestamps();
        });

        // Trip Updates table (for in-transit monitoring)
        Schema::create('trip_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->enum('update_type', ['status', 'location', 'delay', 'incident', 'completed']);
            $table->text('message');
            $table->string('location')->nullable();
            $table->enum('reported_by', ['driver', 'dispatcher']);
            $table->timestamps();
        });

        // Client Notifications table
        Schema::create('client_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->enum('notification_type', ['assignment', 'in-transit', 'delay', 'completed']);
            $table->text('message');
            $table->enum('method', ['sms', 'email', 'call']);
            $table->boolean('sent')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_notifications');
        Schema::dropIfExists('trip_updates');
        Schema::dropIfExists('trips');
        Schema::dropIfExists('delivery_requests');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('clients');
    }
};
