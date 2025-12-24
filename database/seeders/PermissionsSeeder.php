<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Define permissions by module (73 total across 14 modules)
        $modules = [
            'users' => ['view', 'create', 'edit', 'delete', 'export'],
            'departments' => ['view', 'create', 'edit', 'delete'],
            'bookings' => ['view', 'create', 'edit', 'delete', 'confirm', 'cancel'],
            'offers' => ['view', 'create', 'edit', 'delete', 'publish'],
            'invoices' => ['view', 'create', 'edit', 'delete', 'pay'],
            'payments' => ['view', 'create', 'record'],
            'vouchers' => ['view', 'create', 'generate', 'redeem'],
            'hotels' => ['view', 'create', 'edit', 'delete'],
            'flights' => ['view', 'create', 'edit', 'delete'],
            'cars' => ['view', 'create', 'edit', 'delete'],
            'tours' => ['view', 'create', 'edit', 'delete'],
            'customers' => ['view', 'create', 'edit', 'delete'],
            'reports' => ['view', 'export'],
            'settings' => ['view', 'edit'],
        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(
                    ['name' => "{$action}_{$module}", 'guard_name' => 'sanctum'],
                );
            }
        }
    }
}

