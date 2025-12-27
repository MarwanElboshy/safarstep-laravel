<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->after('created_by');
            $table->unsignedBigInteger('company_id')->nullable()->after('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->index(['customer_id', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['company_id']);
            $table->dropIndex(['customer_id', 'company_id']);
            $table->dropColumn(['customer_id', 'company_id']);
        });
    }
};
