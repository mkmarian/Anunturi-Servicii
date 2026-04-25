<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('service_categories')->restrictOnDelete();
            $table->foreignId('county_id')->constrained('counties')->restrictOnDelete();
            $table->foreignId('city_id')->constrained('cities')->restrictOnDelete();
            $table->string('title', 180);
            $table->string('slug', 220)->unique();
            $table->text('description');
            $table->string('budget_type', 32)->nullable();
            $table->decimal('budget_from', 10, 2)->nullable();
            $table->decimal('budget_to', 10, 2)->nullable();
            $table->char('currency', 3)->default('RON');
            $table->date('desired_date')->nullable();
            $table->string('status', 32)->default('draft');
            $table->string('moderation_note', 500)->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('responses_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Indecsi critici
            $table->index(['status', 'published_at', 'id']);
            $table->index(['category_id', 'city_id', 'status', 'published_at']);
            $table->index(['county_id', 'city_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
