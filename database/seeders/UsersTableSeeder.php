<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'tenant_id' => 1,
                'name' => 'SafarStep Admin',
                'email' => 'iosmarawan@gmail.com',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
                'role' => 'super_admin',
            ],
            [
                'tenant_id' => 1,
                'name' => 'Operations Manager',
                'email' => 'ops@safarstep.com',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
                'role' => 'admin',
            ],
            [
                'tenant_id' => 1,
                'name' => 'Booking Agent',
                'email' => 'agent@safarstep.com',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
                'role' => 'manager',
            ],
            [
                'tenant_id' => 1,
                'name' => 'Sarah Johnson',
                'email' => 'sarah@safarstep.com',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
                'role' => 'employee',
            ],
            [
                'tenant_id' => 1,
                'name' => 'Michael Brown',
                'email' => 'michael@safarstep.com',
                'password' => Hash::make('password'),
                'status' => 'inactive',
                'email_verified_at' => now(),
                'role' => 'employee',
            ],
            [
                'tenant_id' => 1,
                'name' => 'Emily Davis',
                'email' => 'emily@safarstep.com',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
                'role' => 'manager',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'] ?? null;
            unset($userData['role']);
            
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
            
            // Assign role if specified
            if ($role) {
                $roleModel = Role::where('name', $role)
                    ->where('tenant_id', $userData['tenant_id'])
                    ->first();
                    
                if ($roleModel) {
                    $user->syncRoles([$roleModel]);
                }
            }
        }
    }
}
