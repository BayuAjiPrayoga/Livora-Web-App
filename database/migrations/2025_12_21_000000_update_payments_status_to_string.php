<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change status column from ENUM to STRING to support various Midtrans statuses
        // We use raw SQL because doctrine/dbal might not be installed
        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE payments MODIFY COLUMN status VARCHAR(50) DEFAULT 'pending'");
        } else {
            // Fallback for SQLite (requires recreating table usually, but we try standard schema builder just in case dbal is present properly or ignore)
            Schema::table('payments', function (Blueprint $table) {
                // Without dbal this throws exception if we try to change().
                // Assuming MySQL for production.
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('database.default') === 'mysql') {
            // Revert to ENUM is risky if data contains non-enum values.
            // keeping as string is safer.
        }
    }
};
