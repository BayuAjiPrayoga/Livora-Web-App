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
            // First, rename existing columns to avoid conflicts
            $table->renameColumn('start_date', 'check_in_date');
            $table->renameColumn('end_date', 'check_out_date');
            $table->renameColumn('duration', 'duration_months');
            $table->renameColumn('total_price', 'final_amount');
        });

        Schema::table('bookings', function (Blueprint $table) {
            // Add new columns after renaming to avoid conflicts
            $table->foreignId('boarding_house_id')->nullable()->after('room_id')->constrained()->onDelete('cascade');
            $table->string('booking_code')->nullable()->after('boarding_house_id');
            
            // Add missing date/duration fields
            $table->integer('duration_days')->default(0)->after('duration_months');
            
            // Pricing fields
            $table->decimal('monthly_price', 10, 2)->after('duration_days');
            $table->decimal('total_amount', 10, 2)->after('monthly_price');
            $table->decimal('deposit_amount', 10, 2)->default(0)->after('total_amount');
            $table->decimal('admin_fee', 10, 2)->default(0)->after('deposit_amount');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('admin_fee');
            
            // Update status enum to match model
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled', 'expired'])
                  ->default('pending')->change();
            $table->enum('booking_type', ['monthly', 'yearly', 'daily'])->default('monthly')->after('status');
            
            // Tenant information
            $table->string('tenant_name')->nullable()->after('booking_type');
            $table->string('tenant_phone')->nullable()->after('tenant_name');
            $table->string('tenant_email')->nullable()->after('tenant_phone');
            $table->string('tenant_identity_number')->nullable()->after('tenant_email');
            $table->text('tenant_address')->nullable()->after('tenant_identity_number');
            
            // Emergency contact
            $table->string('emergency_contact_name')->nullable()->after('tenant_address');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relation')->nullable()->after('emergency_contact_phone');
            
            // Additional fields
            $table->text('special_requests')->nullable()->after('emergency_contact_relation');
            
            // Timestamps for booking lifecycle
            $table->timestamp('confirmed_at')->nullable()->after('notes');
            $table->timestamp('actual_check_in_date')->nullable()->after('confirmed_at');
            $table->timestamp('actual_check_out_date')->nullable()->after('actual_check_in_date');
            $table->timestamp('cancelled_at')->nullable()->after('actual_check_out_date');
            $table->text('cancellation_reason')->nullable()->after('cancelled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Remove new columns
            $table->dropForeign(['boarding_house_id']);
            $table->dropColumn([
                'boarding_house_id', 'booking_code', 'duration_days', 'monthly_price', 
                'total_amount', 'deposit_amount', 'admin_fee', 'discount_amount', 
                'booking_type', 'tenant_name', 'tenant_phone', 'tenant_email', 
                'tenant_identity_number', 'tenant_address', 'emergency_contact_name', 
                'emergency_contact_phone', 'emergency_contact_relation', 'special_requests', 
                'confirmed_at', 'actual_check_in_date', 'actual_check_out_date', 
                'cancelled_at', 'cancellation_reason'
            ]);
            
            // Rename columns back
            $table->renameColumn('check_in_date', 'start_date');
            $table->renameColumn('check_out_date', 'end_date');
            $table->renameColumn('duration_months', 'duration');
            $table->renameColumn('final_amount', 'total_price');
            
            // Restore old status enum
            $table->enum('status', ['pending', 'confirmed', 'active', 'completed', 'cancelled'])
                  ->default('pending')->change();
        });
    }
};
