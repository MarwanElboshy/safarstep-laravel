<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenant_hotels', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();

            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnDelete();

            $table->string('currency', 10)->default('USD');
            $table->decimal('base_price_per_night', 10, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('extra_bed_price', 10, 2)->nullable();
            $table->string('meal_plan', 50)->nullable(); // BB, HB, FB, AI
            $table->json('room_types')->nullable(); // [{name, capacity, base_price}]
            $table->string('status', 20)->default('active');

            $table->timestamps();

            $table->unique(['tenant_id', 'hotel_id']);
            $table->index(['tenant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_hotels');
    }
};
