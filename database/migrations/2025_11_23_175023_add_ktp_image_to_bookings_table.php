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
        Schema::table('bookings', function (Blueprint $table) {
            // Check if columns don't already exist before adding
            if (!Schema::hasColumn('bookings', 'tenant_identity_number')) {
                $table->string('tenant_identity_number', 20)->nullable();
            }
            if (!Schema::hasColumn('bookings', 'ktp_image')) {
                $table->string('ktp_image')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['ktp_image', 'tenant_identity_number']);
        });
    }
};
