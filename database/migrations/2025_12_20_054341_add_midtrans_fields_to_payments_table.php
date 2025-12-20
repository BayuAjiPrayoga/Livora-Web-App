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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('proof_image');
            $table->string('order_id')->unique()->nullable()->after('snap_token');
            $table->string('transaction_id')->nullable()->after('order_id');
            $table->string('payment_type')->nullable()->after('transaction_id');
            $table->string('payment_method')->nullable()->after('payment_type');
            $table->string('midtrans_status')->nullable()->after('payment_method');
            $table->timestamp('transaction_time')->nullable()->after('midtrans_status');
            $table->text('midtrans_response')->nullable()->after('transaction_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'snap_token',
                'order_id',
                'transaction_id',
                'payment_type',
                'payment_method',
                'midtrans_status',
                'transaction_time',
                'midtrans_response'
            ]);
        });
    }
};
