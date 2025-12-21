<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('delivery_requests', 'container_number')) {
                $table->string('container_number')->nullable()->after('booking_number');
            }
            if (!Schema::hasColumn('delivery_requests', 'seal_number')) {
                $table->string('seal_number')->nullable()->after('container_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('delivery_requests', function (Blueprint $table) {
            if (Schema::hasColumn('delivery_requests', 'container_number')) {
                $table->dropColumn('container_number');
            }
            if (Schema::hasColumn('delivery_requests', 'seal_number')) {
                $table->dropColumn('seal_number');
            }
        });
    }
};
