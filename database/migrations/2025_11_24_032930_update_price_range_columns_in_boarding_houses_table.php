<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('boarding_houses', function (Blueprint $table) {
            // Change decimal(10,2) to decimal(12,2) to support up to 999,999,999.99
            $table->decimal('price_range_start', 12, 2)->nullable()->change();
            $table->decimal('price_range_end', 12, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boarding_houses', function (Blueprint $table) {
            // Revert back to decimal(10,2)
            $table->decimal('price_range_start', 10, 2)->nullable()->change();
            $table->decimal('price_range_end', 10, 2)->nullable()->change();
        });
    }
};
