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
        // Add Midtrans statuses to payments table
        // Existing: pending, verified, rejected
        // New: settlement, expired, cancelled, deny, refund, failed
        DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('pending', 'verified', 'rejected', 'settlement', 'expired', 'cancelled', 'deny', 'refund', 'failed') NOT NULL DEFAULT 'pending'");

        // Add new columns for Midtrans tracking if they don't exist
        if (!Schema::hasColumn('payments', 'midtrans_order_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->string('midtrans_token')->nullable()->after('status');
                $table->string('midtrans_redirect_url')->nullable()->after('midtrans_token');
                $table->string('midtrans_order_id')->nullable()->after('midtrans_redirect_url');
                $table->string('payment_type')->default('manual')->after('status');
                $table->string('transaction_id')->nullable()->after('midtrans_order_id');
                $table->string('midtrans_status')->nullable()->after('transaction_id');
                $table->text('midtrans_response')->nullable()->after('midtrans_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting enum modification is risky, skipping for safety
    }
};
