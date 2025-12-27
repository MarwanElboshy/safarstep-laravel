<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\RoleResource;
use App\Services\Tenant\TenantContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * List all roles for the current tenant
     */
    public function index(): JsonResponse
    {
        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        // Strict tenant-level roles
        $roles = Role::query()
            ->where('tenant_id', $tenant->id)
            ->withCount('permissions')
            ->orderBy('name')
            ->get();

        return RoleResource::collection($roles)->response();
    }

    /**
     * Return permissions for a single role
     */
    public function permissions(Role $role): JsonResponse
    {
        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        abort_unless($role->tenant_id === $tenant->id, 404, 'Role not found for this tenant');

        $role->load('permissions');

        return (new RoleResource($role))->response();
    }

    /**
     * Sync permissions for a role
     */
    public function syncPermissions(Request $request, Role $role): JsonResponse
    {
        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        abort_unless($role->tenant_id === $tenant->id, 404, 'Role not found for this tenant');

        $data = $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $permissions = Permission::query()
            ->whereIn('id', $data['permissions'])
            ->get();

        $role->syncPermissions($permissions);

        $role->load('permissions');

        return response()->json([
            'success' => true,
            'data' => new RoleResource($role),
        ]);
    }
}
