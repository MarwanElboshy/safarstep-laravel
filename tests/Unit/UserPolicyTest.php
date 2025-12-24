<?php

namespace Tests\Unit;

use App\Models\Tenant;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Services\Tenant\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;

    private PermissionRegistrar $registrar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registrar = app(PermissionRegistrar::class);
        $this->registrar->forgetCachedPermissions();
        config(['permission.teams' => true, 'permission.column_names.team_foreign_key' => 'tenant_id']);
        $this->registrar->teams = true;
        $this->registrar->teamsKey = 'tenant_id';
    }

    public function test_user_can_only_view_users_in_own_tenant(): void
    {
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

        $this->registrar->setPermissionsTeamId($tenantA->id);
        $actor = User::factory()->create([
            'tenant_id' => $tenantA->id,
            'department_id' => null,
        ]);
        $actor->givePermissionTo($permission);

        $targetSameTenant = User::factory()->create(['tenant_id' => $tenantA->id]);
        $targetOtherTenant = User::factory()->create(['tenant_id' => $tenantB->id]);

        app()->instance(TenantContext::class, new TenantContext($tenantA->id));

        $policy = new UserPolicy();

        $this->assertTrue($policy->view($actor, $targetSameTenant));

        app()->instance(TenantContext::class, new TenantContext($tenantB->id));
        $this->registrar->setPermissionsTeamId($tenantB->id);
        $actor->forgetCachedPermissions();

        $this->assertFalse($policy->view($actor, $targetOtherTenant));
    }
}
