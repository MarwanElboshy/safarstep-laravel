<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_code')->unique();
            $table->string('airline');
            $table->string('from_city');
            $table->string('to_city');
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time');
            $table->integer('duration_minutes');
            $table->integer('stops')->default(0);
            $table->integer('total_seats')->default(180);
            $table->integer('available_seats')->default(180);
            $table->decimal('base_fare', 10, 2)->default(0);
            $table->json('amenities')->nullable(); // WiFi, meals, etc.
            $table->json('baggage_policy')->nullable();
            $table->enum('status', ['available', 'booked', 'cancelled'])->default('available');
            $table->timestamps();
            $table->index(['from_city', 'to_city']);
            $table->index('departure_time');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
