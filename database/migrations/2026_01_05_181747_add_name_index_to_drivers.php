<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add a simple index on the name column for faster lookups
        Schema::table('drivers', function (Blueprint $table) {
            $table->index('name');
        });
    }

    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });
    }
};
