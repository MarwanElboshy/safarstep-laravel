<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offer_destinations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');
            $table->unsignedBigInteger('destination_id');
            $table->integer('sequence');
            $table->integer('nights')->default(1);
            $table->text('activities')->nullable(); // JSON array
            $table->timestamps();
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->foreign('destination_id')->references('id')->on('destinations')->onDelete('cascade');
            $table->unique(['offer_id', 'destination_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_destinations');
    }
};
