<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('destination_id');
            $table->string('name');
            $table->string('license_plate')->unique();
            $table->enum('vehicle_type', ['economy', 'compact', 'sedan', 'suv', 'van', 'luxury'])->default('sedan');
            $table->integer('capacity')->default(5);
            $table->integer('luggage_capacity')->default(2);
            $table->decimal('daily_rate', 10, 2)->default(0);
            $table->json('features')->nullable(); // AC, auto transmission, etc.
            $table->json('policies')->nullable();
            $table->enum('status', ['available', 'rented', 'maintenance', 'inactive'])->default('available');
            $table->timestamps();
            $table->foreign('destination_id')->references('id')->on('destinations')->onDelete('cascade');
            $table->index('destination_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
