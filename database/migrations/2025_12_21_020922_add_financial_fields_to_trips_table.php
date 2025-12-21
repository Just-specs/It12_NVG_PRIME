<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            // Financial tracking fields
            $table->string('waybill_number')->nullable()->after('delivery_request_id');
            $table->decimal('trip_rate', 10, 2)->nullable()->after('waybill_number')->comment('Price charged to client');
            $table->decimal('additional_charge_20ft', 10, 2)->default(0)->after('trip_rate')->comment('Additional charge for 20ft');
            $table->decimal('additional_charge_50', 10, 2)->default(0)->after('additional_charge_20ft')->comment('Additional charge 50');
            $table->decimal('driver_payroll', 10, 2)->nullable()->after('additional_charge_50')->comment('Amount paid to driver');
            $table->decimal('driver_allowance', 10, 2)->nullable()->after('driver_payroll')->comment('Driver allowance');
            $table->string('official_receipt_number')->nullable()->after('driver_allowance')->comment('OR number');
            
            // Add index for waybill lookup
            $table->index('waybill_number');
        });
    }

    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropIndex(['waybill_number']);
            $table->dropColumn([
                'waybill_number',
                'trip_rate',
                'additional_charge_20ft',
                'additional_charge_50',
                'driver_payroll',
                'driver_allowance',
                'official_receipt_number'
            ]);
        });
    }
};
