<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('delivery_requests', 'eir_number')) {
                $table->string('eir_number')->nullable()->after('atw_reference');
            }
            if (!Schema::hasColumn('delivery_requests', 'booking_number')) {
                $table->string('booking_number')->nullable()->after('eir_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('delivery_requests', function (Blueprint $table) {
            if (Schema::hasColumn('delivery_requests', 'eir_number')) {
                $table->dropColumn('eir_number');
            }
            if (Schema::hasColumn('delivery_requests', 'booking_number')) {
                $table->dropColumn('booking_number');
            }
        });
    }
};
