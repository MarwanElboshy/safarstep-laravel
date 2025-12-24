<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'super_admin',
            'admin',
            'manager',
            'employee',
        ];

        $adminPermissionNames = [
            'view_users', 'create_users', 'edit_users', 'delete_users',
            'view_departments', 'create_departments', 'edit_departments',
            'view_bookings', 'create_bookings', 'edit_bookings', 'confirm_bookings', 'cancel_bookings',
            'view_offers', 'create_offers', 'edit_offers', 'publish_offers',
            'view_invoices', 'create_invoices', 'edit_invoices',
            'view_payments', 'create_payments',
            'view_customers', 'create_customers', 'edit_customers',
            'view_reports', 'export_reports',
        ];

        $tenants = Tenant::all();
        if ($tenants->isEmpty()) {
            return;
        }

        $allPermissions = Permission::all();
        $adminPermissions = Permission::whereIn('name', $adminPermissionNames)->get();

        foreach ($tenants as $tenant) {
            $tenantRoles = [];

            foreach ($roles as $name) {
                $tenantRoles[$name] = Role::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'sanctum', 'tenant_id' => $tenant->id]
                );
            }

            if ($tenantRoles['super_admin']) {
                $tenantRoles['super_admin']->syncPermissions($allPermissions);
            }

            if ($tenantRoles['admin']) {
                $tenantRoles['admin']->syncPermissions($adminPermissions);
            }
        }
    }
}

