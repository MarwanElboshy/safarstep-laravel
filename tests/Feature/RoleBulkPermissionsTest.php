<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleBulkPermissionsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private $tenantId;

    protected function setUp(): void
    {
        parent::setUp();

        // Bypass all authorization
        Gate::before(fn() => true);

        // Create tenant record (required for FK constraint)
        $this->tenantId = \Illuminate\Support\Str::uuid()->toString();
        DB::table('tenants')->insert([
            'id' => $this->tenantId,
            'name' => 'Test Tenant',
            'slug' => 'test-tenant',
            'settings' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create user with tenant
        $this->user = User::factory()->create([
            'tenant_id' => $this->tenantId,
        ]);

        // Create permissions
        Permission::create(['name' => 'view_users', 'display_name' => 'View Users']);
        Permission::create(['name' => 'edit_users', 'display_name' => 'Edit Users']);
        Permission::create(['name' => 'delete_users', 'display_name' => 'Delete Users']);
        Permission::create(['name' => 'view_roles', 'display_name' => 'View Roles']);
    }

    public function test_can_add_permissions_to_multiple_roles()
    {
        // Create roles (roles table doesn't have FK constraint on tenant_id in Spatie package)
        $role1 = Role::create(['name' => 'Editor', 'tenant_id' => $this->tenantId, 'guard_name' => 'web']);
        $role2 = Role::create(['name' => 'Moderator', 'tenant_id' => $this->tenantId, 'guard_name' => 'web']);

        // Get permissions
        $viewUsers = Permission::where('name', 'view_users')->first();
        $editUsers = Permission::where('name', 'edit_users')->first();

        // Add initial permission to role1
        $role1->givePermissionTo($viewUsers);

        $response = $this->actingAs($this->user)
            ->withHeaders([
                'X-Tenant-ID' => $this->tenantId,
            ])
            ->postJson('/api/v1/roles/bulk/add-permissions', [
                'role_ids' => [$role1->id, $role2->id],
                'permission_ids' => [$viewUsers->id, $editUsers->id],
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'roles_updated' => 2,
                'permissions_added' => 2,
            ]);

        // Verify permissions were added
        $this->assertTrue($role1->hasPermissionTo($viewUsers));
        $this->assertTrue($role1->hasPermissionTo($editUsers));
        $this->assertTrue($role2->hasPermissionTo($viewUsers));
        $this->assertTrue($role2->hasPermissionTo($editUsers));
    }

    public function test_can_remove_permissions_from_multiple_roles()
    {
        // Create roles with permissions
        $role1 = Role::create(['name' => 'Editor', 'tenant_id' => $this->tenantId, 'guard_name' => 'web']);
        $role2 = Role::create(['name' => 'Moderator', 'tenant_id' => $this->tenantId, 'guard_name' => 'web']);

        $viewUsers = Permission::where('name', 'view_users')->first();
        $editUsers = Permission::where('name', 'edit_users')->first();
        $deleteUsers = Permission::where('name', 'delete_users')->first();

        $role1->givePermissionTo([$viewUsers, $editUsers, $deleteUsers]);
        $role2->givePermissionTo([$viewUsers, $editUsers]);

        $response = $this->actingAs($this->user)
            ->withHeaders([
                'X-Tenant-ID' => $this->tenantId,
            ])
            ->postJson('/api/v1/roles/bulk/remove-permissions', [
                'role_ids' => [$role1->id, $role2->id],
                'permission_ids' => [$editUsers->id],
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'roles_updated' => 2,
                'permissions_removed' => 1,
            ]);

        // Verify permissions were removed
        $this->assertTrue($role1->hasPermissionTo($viewUsers));
        $this->assertFalse($role1->hasPermissionTo($editUsers));
        $this->assertTrue($role1->hasPermissionTo($deleteUsers));
        $this->assertTrue($role2->hasPermissionTo($viewUsers));
        $this->assertFalse($role2->hasPermissionTo($editUsers));
    }

    public function test_can_sync_permissions_to_multiple_roles()
    {
        // Create roles with different permissions
        $role1 = Role::create(['name' => 'Editor', 'tenant_id' => $this->tenantId, 'guard_name' => 'web']);
        $role2 = Role::create(['name' => 'Moderator', 'tenant_id' => $this->tenantId, 'guard_name' => 'web']);

        $viewUsers = Permission::where('name', 'view_users')->first();
        $editUsers = Permission::where('name', 'edit_users')->first();
        $deleteUsers = Permission::where('name', 'delete_users')->first();
        $viewRoles = Permission::where('name', 'view_roles')->first();

        $role1->givePermissionTo([$viewUsers, $editUsers, $deleteUsers]);
        $role2->givePermissionTo([$viewUsers]);

        // Sync to only viewRoles and viewUsers
        $response = $this->actingAs($this->user)
            ->withHeaders([
                'X-Tenant-ID' => $this->tenantId,
            ])
            ->postJson('/api/v1/roles/bulk/sync-permissions', [
                'role_ids' => [$role1->id, $role2->id],
                'permission_ids' => [$viewUsers->id, $viewRoles->id],
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'roles_updated' => 2,
                'permissions_synced' => 2,
            ]);

        // Verify permissions were synced (old ones removed, new ones added)
        $this->assertTrue($role1->hasPermissionTo($viewUsers));
        $this->assertFalse($role1->hasPermissionTo($editUsers));
        $this->assertFalse($role1->hasPermissionTo($deleteUsers));
        $this->assertTrue($role1->hasPermissionTo($viewRoles));

        $this->assertTrue($role2->hasPermissionTo($viewUsers));
        $this->assertTrue($role2->hasPermissionTo($viewRoles));
    }

    public function test_cannot_bulk_update_roles_from_different_tenant()
    {
        // Create another tenant
        $otherTenantId = \Illuminate\Support\Str::uuid()->toString();
        DB::table('tenants')->insert([
            'id' => $otherTenantId,
            'name' => 'Other Tenant',
            'slug' => 'other-tenant',
            'settings' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create roles in different tenants
        $otherTenantRole = Role::create(['name' => 'Admin', 'tenant_id' => $otherTenantId, 'guard_name' => 'web']);
        $myRole = Role::create(['name' => 'Editor', 'tenant_id' => $this->tenantId, 'guard_name' => 'web']);

        $viewUsers = Permission::where('name', 'view_users')->first();

        $response = $this->actingAs($this->user)
            ->withHeaders([
                'X-Tenant-ID' => $this->tenantId,
            ])
            ->postJson('/api/v1/roles/bulk/add-permissions', [
                'role_ids' => [$myRole->id, $otherTenantRole->id],
                'permission_ids' => [$viewUsers->id],
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'One or more roles do not belong to this tenant',
            ]);
    }

    public function test_validates_required_fields()
    {
        $response = $this->actingAs($this->user)
            ->withHeaders([
                'X-Tenant-ID' => $this->tenantId,
            ])
            ->postJson('/api/v1/roles/bulk/add-permissions', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['role_ids', 'permission_ids']);
    }

    public function test_validates_minimum_array_length()
    {
        $response = $this->actingAs($this->user)
            ->withHeaders([
                'X-Tenant-ID' => $this->tenantId,
            ])
            ->postJson('/api/v1/roles/bulk/add-permissions', [
                'role_ids' => [],
                'permission_ids' => [],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['role_ids', 'permission_ids']);
    }

    public function test_validates_role_exists()
    {
        $viewUsers = Permission::where('name', 'view_users')->first();

        $response = $this->actingAs($this->user)
            ->withHeaders([
                'X-Tenant-ID' => $this->tenantId,
            ])
            ->postJson('/api/v1/roles/bulk/add-permissions', [
                'role_ids' => [99999], // Non-existent role
                'permission_ids' => [$viewUsers->id],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['role_ids.0']);
    }

    public function test_validates_permission_exists()
    {
        $role = Role::create(['name' => 'Editor', 'tenant_id' => $this->tenantId, 'guard_name' => 'web']);

        $response = $this->actingAs($this->user)
            ->withHeaders([
                'X-Tenant-ID' => $this->tenantId,
            ])
            ->postJson('/api/v1/roles/bulk/add-permissions', [
                'role_ids' => [$role->id],
                'permission_ids' => [99999], // Non-existent permission
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['permission_ids.0']);
    }
}
