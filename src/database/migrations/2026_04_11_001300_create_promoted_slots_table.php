<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promoted_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained('listings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('promotion_type', 50);
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->string('status', 32)->default('scheduled');
            $table->decimal('price_amount', 10, 2)->default(0);
            $table->char('currency', 3)->default('RON');
            $table->timestamps();

            $table->index(['status', 'starts_at', 'ends_at']);
            $table->index(['listing_id', 'status']);
            $table->index(['promotion_type', 'status', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promoted_slots');
    }
};
