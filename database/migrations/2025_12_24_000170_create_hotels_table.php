<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('destination_id');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->integer('stars')->default(3); // 1-5 stars
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->json('amenities')->nullable(); // JSON array
            $table->json('policies')->nullable(); // Cancellation, check-in, etc.
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->decimal('base_price_per_night', 10, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
            $table->timestamps();
            $table->foreign('destination_id')->references('id')->on('destinations')->onDelete('cascade');
            $table->index('destination_id');
            $table->index('status');
            $table->unique(['destination_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
