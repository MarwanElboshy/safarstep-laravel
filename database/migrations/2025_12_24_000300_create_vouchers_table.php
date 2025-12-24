<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id');
            $table->unsignedBigInteger('invoice_id');
            $table->string('code')->unique();
            $table->string('qr_code')->unique();
            $table->text('qr_code_image')->nullable(); // Base64 or path
            $table->enum('status', ['pending', 'issued', 'redeemed', 'expired', 'cancelled'])->default('pending');
            $table->decimal('value', 12, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->date('valid_from');
            $table->date('valid_until');
            $table->string('recipient_name')->nullable();
            $table->string('recipient_email')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('redeemed_at')->nullable();
            $table->text('redemption_notes')->nullable();
            $table->timestamps();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->index('tenant_id');
            $table->index('status');
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
