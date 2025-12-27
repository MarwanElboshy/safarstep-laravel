<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Tenant\TenantContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleBulkController extends Controller
{
    /**
     * Bulk add permissions to multiple roles
     */
    public function addPermissions(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'required|integer|exists:roles,id',
            'permission_ids' => 'required|array|min:1',
            'permission_ids.*' => 'required|integer|exists:permissions,id',
        ]);

        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        // Verify all roles belong to this tenant
        $roles = Role::query()
            ->whereIn('id', $validated['role_ids'])
            ->where('tenant_id', $tenant->id)
            ->get();

        if ($roles->count() !== count($validated['role_ids'])) {
            return response()->json([
                'success' => false,
                'message' => 'One or more roles do not belong to this tenant',
            ], 422);
        }

        // Get permissions
        $permissions = Permission::query()
            ->whereIn('id', $validated['permission_ids'])
            ->get();

        if ($permissions->count() !== count($validated['permission_ids'])) {
            return response()->json([
                'success' => false,
                'message' => 'One or more permissions not found',
            ], 422);
        }

        // Add permissions to each role (doesn't remove existing ones)
        DB::transaction(function () use ($roles, $permissions) {
            foreach ($roles as $role) {
                $role->givePermissionTo($permissions);
            }
        });

        return response()->json([
            'success' => true,
            'message' => "Added {$permissions->count()} permission(s) to {$roles->count()} role(s)",
            'roles_updated' => $roles->count(),
            'permissions_added' => $permissions->count(),
        ]);
    }

    /**
     * Bulk remove permissions from multiple roles
     */
    public function removePermissions(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'required|integer|exists:roles,id',
            'permission_ids' => 'required|array|min:1',
            'permission_ids.*' => 'required|integer|exists:permissions,id',
        ]);

        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        // Verify all roles belong to this tenant
        $roles = Role::query()
            ->whereIn('id', $validated['role_ids'])
            ->where('tenant_id', $tenant->id)
            ->get();

        if ($roles->count() !== count($validated['role_ids'])) {
            return response()->json([
                'success' => false,
                'message' => 'One or more roles do not belong to this tenant',
            ], 422);
        }

        // Get permissions
        $permissions = Permission::query()
            ->whereIn('id', $validated['permission_ids'])
            ->get();

        if ($permissions->count() !== count($validated['permission_ids'])) {
            return response()->json([
                'success' => false,
                'message' => 'One or more permissions not found',
            ], 422);
        }

        // Remove permissions from each role
        DB::transaction(function () use ($roles, $permissions) {
            foreach ($roles as $role) {
                $role->revokePermissionTo($permissions);
            }
        });

        return response()->json([
            'success' => true,
            'message' => "Removed {$permissions->count()} permission(s) from {$roles->count()} role(s)",
            'roles_updated' => $roles->count(),
            'permissions_removed' => $permissions->count(),
        ]);
    }

    /**
     * Bulk sync permissions to multiple roles (replaces all existing permissions)
     */
    public function syncPermissions(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'required|integer|exists:roles,id',
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'required|integer|exists:permissions,id',
        ]);

        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        // Verify all roles belong to this tenant
        $roles = Role::query()
            ->whereIn('id', $validated['role_ids'])
            ->where('tenant_id', $tenant->id)
            ->get();

        if ($roles->count() !== count($validated['role_ids'])) {
            return response()->json([
                'success' => false,
                'message' => 'One or more roles do not belong to this tenant',
            ], 422);
        }

        // Get permissions
        $permissions = Permission::query()
            ->whereIn('id', $validated['permission_ids'])
            ->get();

        if ($permissions->count() !== count($validated['permission_ids'])) {
            return response()->json([
                'success' => false,
                'message' => 'One or more permissions not found',
            ], 422);
        }

        // Sync permissions to each role (replaces existing permissions)
        DB::transaction(function () use ($roles, $permissions) {
            foreach ($roles as $role) {
                $role->syncPermissions($permissions);
            }
        });

        return response()->json([
            'success' => true,
            'message' => "Synced {$permissions->count()} permission(s) to {$roles->count()} role(s)",
            'roles_updated' => $roles->count(),
            'permissions_synced' => $permissions->count(),
        ]);
    }
}
