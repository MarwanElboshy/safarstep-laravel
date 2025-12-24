<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id');
            $table->unsignedBigInteger('offer_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('reference_number')->unique();
            $table->integer('number_of_travelers');
            $table->decimal('total_price', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('remaining_amount', 12, 2)->default(0);
            $table->enum('status', ['draft', 'pending', 'confirmed', 'active', 'completed', 'cancelled'])->default('draft');
            $table->date('travel_date');
            $table->date('return_date')->nullable();
            $table->text('special_requests')->nullable();
            $table->json('traveler_details')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->unsignedBigInteger('created_by');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->index('tenant_id');
            $table->index('status');
            $table->index('travel_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
