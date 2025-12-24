<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('add_ons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('category', ['activity', 'insurance', 'transportation', 'meal', 'accommodation', 'service'])->default('activity');
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('pricing_type', ['per_person', 'per_booking', 'per_night'])->default('per_person');
            $table->text('terms')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->index('category');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('add_ons');
    }
};
