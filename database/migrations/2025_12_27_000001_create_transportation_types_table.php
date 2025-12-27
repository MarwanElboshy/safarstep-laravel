<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transportation_types', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id');
            $table->string('name'); // e.g., "Private Car", "Minibus", "Flight", "Train"
            $table->string('slug'); // e.g., "private-car", "minibus", "flight", "train"
            $table->string('icon')->nullable(); // e.g., "car", "bus", "plane", "train"
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0); // For custom ordering
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign key and indexes
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->unique(['tenant_id', 'slug']); // Unique per tenant
            $table->index(['tenant_id', 'is_active']);
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transportation_types');
    }
};
