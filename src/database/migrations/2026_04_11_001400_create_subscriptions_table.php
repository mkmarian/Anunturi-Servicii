<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('plan_code', 50);
            $table->string('status', 32)->default('active');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->dateTime('renews_at')->nullable();
            $table->decimal('price_amount', 10, 2)->default(0);
            $table->char('currency', 3)->default('RON');
            $table->string('provider', 50)->nullable();
            $table->string('provider_reference', 255)->nullable()->index();
            $table->timestamps();

            $table->index(['user_id', 'status', 'ends_at']);
            $table->index(['status', 'renews_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
