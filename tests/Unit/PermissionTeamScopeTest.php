<?php

namespace Tests\Unit;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class PermissionTeamScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_permission_checks_are_scoped_to_tenant_team(): void
    {
        /** @var PermissionRegistrar $registrar */
        $registrar = app(PermissionRegistrar::class);
        $registrar->forgetCachedPermissions();
        $registrar->teams = true;
        $registrar->teamsKey = 'tenant_id';

        config([
            'permission.teams' => true,
            'permission.column_names.team_foreign_key' => 'tenant_id',
        ]);

        $tenantA = Tenant::create([
            'id' => (string) Str::uuid(),
            'name' => 'Tenant A',
            'slug' => 'tenant-a',
        ]);

        $tenantB = Tenant::create([
            'id' => (string) Str::uuid(),
            'name' => 'Tenant B',
            'slug' => 'tenant-b',
        ]);

        $permission = Permission::create([
            'name' => 'view_users',
            'guard_name' => 'sanctum',
        ]);

        $registrar->setPermissionsTeamId($tenantA->id);
        $this->assertSame($tenantA->id, getPermissionsTeamId());
        $roleA = Role::create([
            'tenant_id' => $tenantA->id,
            'name' => 'manager',
            'guard_name' => 'sanctum',
        ]);
        $roleA->givePermissionTo($permission);

        $registrar->setPermissionsTeamId($tenantB->id);
        $roleB = Role::create([
            'tenant_id' => $tenantB->id,
            'name' => 'manager',
            'guard_name' => 'sanctum',
        ]);

        $registrar->setPermissionsTeamId($tenantA->id);
        $user = User::factory()->create([
            'tenant_id' => $tenantA->id,
            'department_id' => null,
        ]);
        $user->assignRole($roleA);
        $user->forgetCachedPermissions();
        $registrar->forgetCachedPermissions();

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $roleA->getKey(),
            'model_id' => $user->getKey(),
            'tenant_id' => $tenantA->id,
        ]);

        $this->assertTrue($user->hasPermissionTo('view_users'));

        $registrar->setPermissionsTeamId($tenantB->id);
        $this->assertSame($tenantB->id, getPermissionsTeamId());
        $registrar->forgetCachedPermissions();
        $user->forgetCachedPermissions();
        $user->unsetRelation('roles');
        $this->assertCount(0, $user->roles);
        $this->assertFalse($user->hasPermissionTo('view_users'));

        $roleB->givePermissionTo($permission);
        $user->assignRole($roleB);
        $registrar->forgetCachedPermissions();
        $user->forgetCachedPermissions();

        $this->assertTrue($user->hasPermissionTo('view_users'));
    }
}
