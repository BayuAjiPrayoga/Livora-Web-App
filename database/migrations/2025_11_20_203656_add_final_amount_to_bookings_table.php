<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Add final_amount column - copy value from total_price initially
            $table->decimal('final_amount', 10, 2)->after('total_price');
        });
        
        // Copy existing total_price values to final_amount
        DB::statement('UPDATE bookings SET final_amount = total_price WHERE final_amount IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('final_amount');
        });
    }
};
