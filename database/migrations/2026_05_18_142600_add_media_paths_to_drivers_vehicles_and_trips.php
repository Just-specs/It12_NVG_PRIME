<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            if (!Schema::hasColumn('drivers', 'photo_path')) {
                $table->string('photo_path')->nullable()->after('license_number');
            }
        });

        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'photo_path')) {
                $table->string('photo_path')->nullable()->after('trailer_type');
            }
        });

        Schema::table('trips', function (Blueprint $table) {
            if (!Schema::hasColumn('trips', 'receipt_path')) {
                $table->string('receipt_path')->nullable()->after('official_receipt_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            if (Schema::hasColumn('trips', 'receipt_path')) {
                $table->dropColumn('receipt_path');
            }
        });

        Schema::table('vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('vehicles', 'photo_path')) {
                $table->dropColumn('photo_path');
            }
        });

        Schema::table('drivers', function (Blueprint $table) {
            if (Schema::hasColumn('drivers', 'photo_path')) {
                $table->dropColumn('photo_path');
            }
        });
    }
};
