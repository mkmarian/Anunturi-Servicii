<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('public_name', 120)->nullable();
            $table->string('company_name', 160)->nullable();
            $table->string('slug', 190)->unique()->nullable();
            $table->text('bio')->nullable();
            $table->foreignId('county_id')->nullable()->constrained('counties')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->string('address_line', 190)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('avatar_path', 255)->nullable();
            $table->string('cover_path', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('whatsapp_phone', 30)->nullable();
            $table->timestamps();

            $table->index(['county_id', 'city_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
