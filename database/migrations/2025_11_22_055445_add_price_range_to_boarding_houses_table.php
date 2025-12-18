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
            $table->decimal('price_range_start', 10, 2)->nullable()->after('is_active');
            $table->decimal('price_range_end', 10, 2)->nullable()->after('price_range_start');
            $table->boolean('is_verified')->default(false)->after('price_range_end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boarding_houses', function (Blueprint $table) {
            $table->dropColumn(['price_range_start', 'price_range_end', 'is_verified']);
        });
    }
};
