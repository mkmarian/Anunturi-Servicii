<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('initiator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('recipient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('listing_id')->nullable()->constrained('listings')->nullOnDelete();
            $table->foreignId('service_request_id')->nullable()->constrained('service_requests')->nullOnDelete();
            $table->string('started_by_role', 32)->nullable();
            $table->string('status', 32)->default('active');
            $table->timestamp('last_message_at')->nullable();
            $table->unsignedBigInteger('last_message_id')->nullable();
            $table->timestamps();

            // Un singur fir de conversatie per pereche utilizatori + context
            $table->unique(['initiator_id', 'recipient_id', 'listing_id'], 'uq_conv_listing');
            $table->unique(['initiator_id', 'recipient_id', 'service_request_id'], 'uq_conv_request');

            $table->index(['initiator_id', 'status', 'last_message_at']);
            $table->index(['recipient_id', 'status', 'last_message_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
