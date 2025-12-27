<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            // Phase 1: Add only missing fields
            $table->dateTime('eir_datetime')->nullable()->after('official_receipt_number')->comment('Date and time of EIR');
            $table->string('served_by')->nullable()->after('route_instructions')->comment('Branch/Location served (LOR, JUNA, EPOY)');
        });
    }

    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn(['eir_datetime', 'served_by']);
        });
    }
};
