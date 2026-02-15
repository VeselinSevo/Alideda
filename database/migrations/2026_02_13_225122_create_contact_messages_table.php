<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();

            // If logged in -> link sender
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // If guest -> store their email/name
            $table->string('name')->nullable();
            $table->string('email')->nullable();

            $table->string('subject');
            $table->text('message');

            // basic admin flow
            $table->timestamp('read_at')->nullable();
            $table->timestamp('replied_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};