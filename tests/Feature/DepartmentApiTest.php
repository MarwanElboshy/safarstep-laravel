<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Tenant\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class DepartmentApiTest extends TestCase
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

    public function test_index_returns_only_current_tenant_departments(): void
    {
        $tenantA = Tenant::create(['id' => (string) Str::uuid(), 'name' => 'Tenant A', 'slug' => 'tenant-a']);
        $tenantB = Tenant::create(['id' => (string) Str::uuid(), 'name' => 'Tenant B', 'slug' => 'tenant-b']);

        $permission = Permission::create(['name' => 'view_departments', 'guard_name' => 'sanctum']);

        $this->registrar->setPermissionsTeamId($tenantA->id);
        $actor = User::factory()->create(['tenant_id' => $tenantA->id]);
        $actor->givePermissionTo($permission);

        $deptA = Department::create(['tenant_id' => $tenantA->id, 'name' => 'Admin']);
        Department::create(['tenant_id' => $tenantB->id, 'name' => 'Ops']);

        Sanctum::actingAs($actor, ['*']);
        app()->instance(TenantContext::class, new TenantContext($tenantA->id));

        $response = $this->getJson('/api/v1/departments', ['X-Tenant-ID' => $tenantA->id]);

        $response->assertOk();
        $response->assertJsonFragment(['id' => $deptA->id]);
        $response->assertJsonMissing(['tenant_id' => $tenantB->id]);
    }

    public function test_show_department_in_other_tenant_is_forbidden(): void
    {
        $tenantA = Tenant::create(['id' => (string) Str::uuid(), 'name' => 'Tenant A', 'slug' => 'tenant-a']);
        $tenantB = Tenant::create(['id' => (string) Str::uuid(), 'name' => 'Tenant B', 'slug' => 'tenant-b']);

        $permission = Permission::create(['name' => 'view_departments', 'guard_name' => 'sanctum']);

        $this->registrar->setPermissionsTeamId($tenantA->id);
        $actor = User::factory()->create(['tenant_id' => $tenantA->id]);
        $actor->givePermissionTo($permission);

        $deptB = Department::create(['tenant_id' => $tenantB->id, 'name' => 'Ops']);

        Sanctum::actingAs($actor, ['*']);
        app()->instance(TenantContext::class, new TenantContext($tenantA->id));

        $response = $this->getJson('/api/v1/departments/'.$deptB->id, ['X-Tenant-ID' => $tenantA->id]);

        $response->assertForbidden();
    }
}
