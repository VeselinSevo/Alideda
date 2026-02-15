<?php

// database/migrations/xxxx_xx_xx_create_activity_logs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('action');            // npr: user.register, cart.add, order.created
            $table->string('message');           // poruka za tabelu
            $table->string('subject_type')->nullable(); // npr Product, Order
            $table->unsignedBigInteger('subject_id')->nullable();

            $table->ipAddress('ip')->nullable();
            $table->string('user_agent', 255)->nullable();

            $table->timestamps();

            $table->index(['action', 'created_at']);
            $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};