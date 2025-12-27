<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('roles') && !Schema::hasColumn('roles', 'tenant_id')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->uuid('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            });
        }

        if (Schema::hasTable('model_has_roles') && !Schema::hasColumn('model_has_roles', 'tenant_id')) {
            Schema::table('model_has_roles', function (Blueprint $table) {
                $table->uuid('tenant_id')->nullable()->after('model_id');
                $table->index('tenant_id');
            });
        }

        if (Schema::hasTable('model_has_permissions') && !Schema::hasColumn('model_has_permissions', 'tenant_id')) {
            Schema::table('model_has_permissions', function (Blueprint $table) {
                $table->uuid('tenant_id')->nullable()->after('model_id');
                $table->index('tenant_id');
            });
        }
    }

    public function down(): void
    {
        // Dropping columns in SQLite often requires DBAL; perform safe checks
        if (Schema::hasTable('roles') && Schema::hasColumn('roles', 'tenant_id')) {
            Schema::table('roles', function (Blueprint $table) {
                try {
                    $table->dropForeign('roles_tenant_id_foreign');
                } catch (\Exception $e) {
                    // FK may not exist, continue
                }
                if (Schema::hasIndex('roles', 'roles_tenant_id_index')) {
                    $table->dropIndex('roles_tenant_id_index');
                }
                $table->dropColumn('tenant_id');
            });
        }
        if (Schema::hasTable('model_has_roles') && Schema::hasColumn('model_has_roles', 'tenant_id')) {
            Schema::table('model_has_roles', function (Blueprint $table) {
                try {
                    $table->dropForeign('model_has_roles_tenant_id_foreign');
                } catch (\Exception $e) {
                    // FK may not exist, continue
                }
                if (Schema::hasIndex('model_has_roles', 'model_has_roles_tenant_id_index')) {
                    $table->dropIndex('model_has_roles_tenant_id_index');
                }
                $table->dropColumn('tenant_id');
            });
        }
        if (Schema::hasTable('model_has_permissions') && Schema::hasColumn('model_has_permissions', 'tenant_id')) {
            Schema::table('model_has_permissions', function (Blueprint $table) {
                try {
                    $table->dropForeign('model_has_permissions_tenant_id_foreign');
                } catch (\Exception $e) {
                    // FK may not exist, continue
                }
                if (Schema::hasIndex('model_has_permissions', 'model_has_permissions_tenant_id_index')) {
                    $table->dropIndex('model_has_permissions_tenant_id_index');
                }
                $table->dropColumn('tenant_id');
            });
        }
    }
};
