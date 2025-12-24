<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action');
            $table->string('model');
            $table->unsignedBigInteger('model_id');
            $table->json('changes')->nullable(); // Old and new values
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index('tenant_id');
            $table->index('user_id');
            $table->index('action');
            $table->index('model');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
