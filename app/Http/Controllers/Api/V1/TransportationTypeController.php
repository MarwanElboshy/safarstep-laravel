<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\TransportationType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransportationTypeController extends Controller
{
    /**
     * GET /api/v1/transportation-types
     * List all active transportation types for the tenant
     */
    public function index(Request $request): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');

        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'Tenant context required'], 400);
        }

        $types = TransportationType::forTenant($tenantId)
            ->active()
            ->get(['id', 'name', 'slug', 'icon', 'description', 'sort_order']);

        return response()->json(['success' => true, 'data' => $types]);
    }

    /**
     * POST /api/v1/transportation-types
     * Create a new transportation type for the tenant
     */
    public function store(Request $request): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');

        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'Tenant context required'], 400);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:transportation_types,slug',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $type = TransportationType::create([
            'tenant_id' => $tenantId,
            ...$validated
        ]);

        return response()->json(['success' => true, 'data' => $type], 201);
    }

    /**
     * PUT /api/v1/transportation-types/{id}
     * Update a transportation type
     */
    public function update(Request $request, TransportationType $transportationType): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');

        if ($transportationType->tenant_id !== $tenantId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'string|max:255',
            'slug' => 'string|max:255|unique:transportation_types,slug,' . $transportationType->id,
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $transportationType->update($validated);

        return response()->json(['success' => true, 'data' => $transportationType]);
    }

    /**
     * DELETE /api/v1/transportation-types/{id}
     * Delete a transportation type
     */
    public function destroy(Request $request, TransportationType $transportationType): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');

        if ($transportationType->tenant_id !== $tenantId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $transportationType->delete();

        return response()->json(['success' => true, 'message' => 'Transportation type deleted']);
    }
}
