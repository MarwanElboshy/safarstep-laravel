<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class FreshTenantSetupSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Reset RBAC and users for the first tenant and reseed a full setup.
     */
    public function run(): void
    {
        // Ensure base tenants exist
        $this->call(TenantSeeder::class);

        // Resolve the primary tenant to reset (SafarStep)
        $tenant = Tenant::where('slug', 'safarstep')->first();
        if (!$tenant) {
            $this->command->error('Primary tenant "safarstep" not found. Aborting reset.');
            return;
        }

        $this->command->warn("Resetting RBAC and users for tenant: {$tenant->name} ({$tenant->id})");

        DB::beginTransaction();
        try {
            // Disable foreign key checks when on MySQL/MariaDB to simplify deletes
            $driver = DB::getDriverName();
            $isMySql = in_array($driver, ['mysql', 'mariadb']);
            if ($isMySql) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            }

            // Collect role IDs for this tenant
            $roleIds = Role::where('tenant_id', $tenant->id)->pluck('id')->all();

            // Delete role-permission assignments for tenant roles
            if (!empty($roleIds)) {
                DB::table('role_has_permissions')->whereIn('role_id', $roleIds)->delete();
            }

            // Delete model-role and model-permission assignments scoped by tenant
            DB::table('model_has_roles')->where('tenant_id', $tenant->id)->delete();
            DB::table('model_has_permissions')->where('tenant_id', $tenant->id)->delete();

            // Delete users and departments for the tenant
            User::where('tenant_id', $tenant->id)->delete();
            Department::where('tenant_id', $tenant->id)->delete();

            // Finally, delete roles for the tenant
            Role::where('tenant_id', $tenant->id)->delete();

            if ($isMySql) {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command->error('Reset failed: ' . $e->getMessage());
            throw $e;
        }

        // Reseed permissions, roles, and auth/users for the tenant
        $this->call([
            PermissionsSeeder::class,
            RolesSeeder::class,
            AuthSeeder::class,
        ]);

        $this->command->info('✓ Tenant RBAC and users reset and reseeded successfully.');
    }
}
