<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Create default roles
        $roles = [
            'super_admin' => 'System administrator with full access',
            'admin' => 'Tenant admin with tenant-level access',
            'manager' => 'Department manager with team oversight',
            'employee' => 'Standard employee with limited access',
        ];

        foreach ($roles as $name => $description) {
            Role::firstOrCreate(
                ['name' => $name, 'guard_name' => 'sanctum']
            );
        }

        // Assign all permissions to super_admin
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->syncPermissions(\Spatie\Permission\Models\Permission::all());
        }

        // Assign module-level permissions to admin
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $adminPerms = \Spatie\Permission\Models\Permission::whereIn('name', [
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'view_departments', 'create_departments', 'edit_departments',
                'view_bookings', 'create_bookings', 'edit_bookings', 'confirm_bookings', 'cancel_bookings',
                'view_offers', 'create_offers', 'edit_offers', 'publish_offers',
                'view_invoices', 'create_invoices', 'edit_invoices',
                'view_payments', 'create_payments',
                'view_customers', 'create_customers', 'edit_customers',
                'view_reports', 'export_reports',
            ])->get();
            $admin->syncPermissions($adminPerms);
        }
    }
}

