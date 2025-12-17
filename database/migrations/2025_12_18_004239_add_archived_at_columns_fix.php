<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add archived_at column to delivery_requests if not exists
        if (!Schema::hasColumn('delivery_requests', 'archived_at')) {
            Schema::table('delivery_requests', function (Blueprint $table) {
                $table->timestamp('archived_at')->nullable()->after('updated_at');
            });
        }

        // Add archived_at column to trips if not exists
        if (!Schema::hasColumn('trips', 'archived_at')) {
            Schema::table('trips', function (Blueprint $table) {
                $table->timestamp('archived_at')->nullable()->after('updated_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('delivery_requests', 'archived_at')) {
            Schema::table('delivery_requests', function (Blueprint $table) {
                $table->dropColumn('archived_at');
            });
        }

        if (Schema::hasColumn('trips', 'archived_at')) {
            Schema::table('trips', function (Blueprint $table) {
                $table->dropColumn('archived_at');
            });
        }
    }
};
