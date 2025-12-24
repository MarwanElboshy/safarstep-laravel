<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('destination_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('guide_name')->nullable();
            $table->integer('capacity')->default(20);
            $table->integer('booked_seats')->default(0);
            $table->decimal('price_per_person', 10, 2)->default(0);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('itinerary')->nullable(); // JSON array of activities
            $table->json('includes')->nullable(); // What's included
            $table->enum('status', ['active', 'cancelled', 'archived'])->default('active');
            $table->timestamps();
            $table->foreign('destination_id')->references('id')->on('destinations')->onDelete('cascade');
            $table->index('destination_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
