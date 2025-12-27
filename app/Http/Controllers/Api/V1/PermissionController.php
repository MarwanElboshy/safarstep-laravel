<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PermissionResource;
use App\Services\Tenant\TenantContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * List permissions for the current guard
     */
    public function index(Request $request): JsonResponse
    {
        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        $permissions = Permission::query()
            ->withCount([
                'roles' => function ($query) use ($tenant) {
                    $query->where('tenant_id', $tenant->id);
                }
            ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->get();

        return PermissionResource::collection($permissions)->response();
    }
}
