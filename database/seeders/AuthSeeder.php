<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AuthSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $tenant = Tenant::firstOrCreate(
            ['slug' => 'safarstep'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'SafarStep Tourism',
                'primary_color' => '#2A50BC',
                'secondary_color' => '#10B981',
                'accent_color' => '#1d4ed8',
                'settings' => [
                    'currency' => 'USD',
                    'timezone' => 'UTC',
                    'locale' => 'en',
                    'booking_prefix' => 'BK',
                    'invoice_prefix' => 'INV',
                    'payment_prefix' => 'PAY',
                    'voucher_prefix' => 'VCH',
                    'offer_prefix' => 'OFF',
                ],
            ]
        );

        // Create admin department
        $adminDept = Department::firstOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'Administration'],
            ['description' => 'Executive and administrative staff']
        );

        // Create operations department
        $opsDept = Department::firstOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'Operations'],
            ['description' => 'Tour operations and booking management']
        );

        // Create main admin user with specified credentials
        $admin = User::firstOrCreate(
            ['email' => 'iosmarawan@gmail.com'],
            [
                'name' => 'SafarStep Admin',
                'password' => '23115520++', // Will be hashed automatically
                'tenant_id' => $tenant->id,
                'department_id' => $adminDept->id,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Create additional test users
        $opsManager = User::firstOrCreate(
            ['email' => 'ops@safarstep.com'],
            [
                'name' => 'Operations Manager',
                'password' => 'password123',
                'tenant_id' => $tenant->id,
                'department_id' => $opsDept->id,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $agent = User::firstOrCreate(
            ['email' => 'agent@safarstep.com'],
            [
                'name' => 'Booking Agent',
                'password' => 'password123',
                'tenant_id' => $tenant->id,
                'department_id' => $opsDept->id,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $superAdminRole = Role::where('tenant_id', $tenant->id)->where('name', 'super_admin')->where('guard_name', 'sanctum')->first();
        $managerRole = Role::where('tenant_id', $tenant->id)->where('name', 'manager')->where('guard_name', 'sanctum')->first();
        $employeeRole = Role::where('tenant_id', $tenant->id)->where('name', 'employee')->where('guard_name', 'sanctum')->first();

        app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);

        if ($superAdminRole) {
            $admin->assignRole($superAdminRole);
        }

        if ($managerRole) {
            $opsManager->assignRole($managerRole);
        }

        if ($employeeRole) {
            $agent->assignRole($employeeRole);
        }

        $this->command->info('✓ Created tenant: ' . $tenant->name);
        $this->command->info('✓ Created admin user: iosmarawan@gmail.com');
        $this->command->info('✓ Created 3 users total');
    }
}
