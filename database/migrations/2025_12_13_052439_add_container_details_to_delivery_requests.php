<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_requests', function (Blueprint $table) {
            $table->string('eir_number', 50)->nullable()->after('atw_reference');
            $table->string('booking_number', 50)->nullable()->after('eir_number');
            $table->string('container_number', 50)->nullable()->after('booking_number');
            $table->string('seal_number', 50)->nullable()->after('container_number');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_requests', function (Blueprint $table) {
            $table->dropColumn(['eir_number', 'booking_number', 'container_number', 'seal_number']);
        });
    }
};
