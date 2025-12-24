<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offer_resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');
            $table->string('resource_type'); // 'hotel', 'flight', 'car', 'tour'
            $table->unsignedBigInteger('resource_id');
            $table->integer('sequence');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('price', 12, 2)->default(0);
            $table->json('details')->nullable();
            $table->timestamps();
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->index(['offer_id', 'resource_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_resources');
    }
};
