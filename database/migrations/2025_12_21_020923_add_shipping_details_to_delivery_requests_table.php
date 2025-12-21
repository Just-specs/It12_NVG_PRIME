<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_requests', function (Blueprint $table) {
            // Shipping details
            $table->string('shipping_line')->nullable()->after('container_type')->comment('Shipping company (e.g., WANHAI, CMA)');
            $table->string('shipper_name')->nullable()->after('shipping_line')->comment('Shipper company name');
            $table->time('eir_time')->nullable()->after('preferred_schedule')->comment('Specific EIR time');
            $table->enum('container_status', ['empty', 'loaded', 'return'])->default('loaded')->after('eir_time');
            
            // Dispatcher tracking
            $table->foreignId('dispatcher_id')->nullable()->after('container_status')->constrained('users')->onDelete('set null')->comment('Dispatcher who served this request');
            
            // Add indexes
            $table->index('shipping_line');
            $table->index('dispatcher_id');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_requests', function (Blueprint $table) {
            $table->dropForeign(['dispatcher_id']);
            $table->dropIndex(['shipping_line']);
            $table->dropIndex(['dispatcher_id']);
            $table->dropColumn([
                'shipping_line',
                'shipper_name',
                'eir_time',
                'container_status',
                'dispatcher_id'
            ]);
        });
    }
};
