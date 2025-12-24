<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id');
            $table->unsignedBigInteger('created_by'); // User ID
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('itinerary')->nullable(); // JSON array
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration_days');
            $table->integer('group_size');
            $table->decimal('base_price', 12, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('final_price', 12, 2)->default(0);
            $table->json('includes')->nullable();
            $table->json('excludes')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->enum('status', ['draft', 'published', 'archived', 'expired'])->default('draft');
            $table->integer('views')->default(0);
            $table->timestamps();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->index('tenant_id');
            $table->index('status');
            $table->index('start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
