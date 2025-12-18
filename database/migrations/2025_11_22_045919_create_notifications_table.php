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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // booking_created, payment_verified, ticket_update, etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data (booking_id, payment_id, etc.)
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_email_sent')->default(false);
            $table->boolean('is_push_sent')->default(false);
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->string('action_url')->nullable(); // URL for action button
            $table->timestamps();
            
            $table->index(['user_id', 'read_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
