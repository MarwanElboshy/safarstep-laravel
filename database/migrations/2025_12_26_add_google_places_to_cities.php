<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            if (!Schema::hasColumn('cities', 'place_id')) {
                $table->string('place_id')->nullable()->unique();
            }
            if (!Schema::hasColumn('cities', 'place_data')) {
                $table->json('place_data')->nullable();
            }
            if (!Schema::hasColumn('cities', 'formatted_address')) {
                $table->string('formatted_address')->nullable();
            }
            if (!Schema::hasIndex('cities', 'cities_place_id_index')) {
                $table->index(['place_id']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            if (Schema::hasIndex('cities', 'cities_place_id_index')) {
                $table->dropIndex(['place_id']);
            }
            if (Schema::hasColumn('cities', 'place_id')) {
                $table->dropColumn('place_id');
            }
            if (Schema::hasColumn('cities', 'place_data')) {
                $table->dropColumn('place_data');
            }
            if (Schema::hasColumn('cities', 'formatted_address')) {
                $table->dropColumn('formatted_address');
            }
        });
    }
};
