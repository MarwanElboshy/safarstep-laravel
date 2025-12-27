<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\OfferFeature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * GET /api/v1/tags?type=inclusion&city=Istanbul
     */
    public function index(Request $request): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        
        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'Tenant context required'], 400);
        }

        $validated = $request->validate([
            'type' => 'nullable|in:inclusion,exclusion',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
        ]);

        $query = OfferFeature::where('tenant_id', $tenantId);

        if (isset($validated['type'])) {
            $query->where('type', $validated['type']);
        }

        // Filter by city or country or global
        if (isset($validated['city']) || isset($validated['country'])) {
            $query->where(function($q) use ($validated) {
                $q->where('is_global', true);
                if (isset($validated['city'])) {
                    // This would need proper city_id lookup
                    $q->orWhereNull('city_id');
                }
                if (isset($validated['country'])) {
                    $q->orWhereNull('country_id');
                }
            });
        } else {
            $query->where('is_global', true);
        }

        $tags = $query->orderBy('name')->get();

        return response()->json(['success' => true, 'data' => $tags]);
    }

    /**
     * POST /api/v1/tags
     */
    public function store(Request $request): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        
        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'Tenant context required'], 400);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:inclusion,exclusion',
            'city_id' => 'nullable|integer',
            'country_id' => 'nullable|integer',
            'is_global' => 'nullable|boolean',
        ]);

        $tag = OfferFeature::create([
            'tenant_id' => $tenantId,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'city_id' => $validated['city_id'] ?? null,
            'country_id' => $validated['country_id'] ?? null,
            'is_global' => $validated['is_global'] ?? false,
        ]);

        return response()->json(['success' => true, 'data' => $tag], 201);
    }

    /**
     * PUT /api/v1/tags/{id}
     */
    public function update(Request $request, OfferFeature $tag): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        
        if ($tag->tenant_id !== $tenantId) {
            return response()->json(['success' => false, 'message' => 'Tag not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:inclusion,exclusion',
            'is_global' => 'sometimes|boolean',
        ]);

        $tag->update($validated);

        return response()->json(['success' => true, 'data' => $tag]);
    }

    /**
     * DELETE /api/v1/tags/{id}
     */
    public function destroy(Request $request, OfferFeature $tag): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        
        if ($tag->tenant_id !== $tenantId) {
            return response()->json(['success' => false, 'message' => 'Tag not found'], 404);
        }

        $tag->delete();

        return response()->json(['success' => true, 'message' => 'Tag deleted']);
    }
}
