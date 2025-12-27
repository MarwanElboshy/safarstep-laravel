<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Location\LocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct(protected LocationService $locationService)
    {
    }

    /**
     * POST /api/v1/locations/autocomplete
     * Autocomplete place search (cities, hotels, attractions)
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'input' => 'required|string|min:2|max:100',
            'type' => 'sometimes|in:city,hotel,attraction,all',
        ]);

        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        $this->locationService->setTenantId($tenantId);

        $predictions = $this->locationService->autocomplete($validated['input']);

        return response()->json([
            'success' => true,
            'data' => $predictions,
        ]);
    }

    /**
     * GET /api/v1/locations/search
     * Search for places using text query
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'query' => 'required|string|min:2|max:200',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'country_code' => 'nullable|string|max:5',
        ]);

        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        $this->locationService->setTenantId($tenantId);

        // Build search query with location context
        $searchQuery = $validated['query'];
        if (!empty($validated['city'])) {
            $searchQuery .= ' in ' . $validated['city'];
        }
        if (!empty($validated['country'])) {
            $searchQuery .= ', ' . $validated['country'];
        }

        $options = [];
        if (!empty($validated['country_code'])) {
            $options['components'] = 'country:' . strtoupper($validated['country_code']);
        }

        $results = $this->locationService->autocomplete($searchQuery, $options);

        return response()->json([
            'success' => true,
            'data' => $results,
            'count' => count($results)
        ]);
    }

    /**
     * GET /api/v1/locations/nearby
     * Alias for nearby search (GET method)
     */
    public function searchNearby(Request $request): JsonResponse
    {
        return $this->nearby($request);
    }

    /**
     * GET /api/v1/locations/details/{placeId}
     * Get detailed place information
     */
    public function details(Request $request, string $placeId): JsonResponse
    {
        if (empty($placeId)) {
            return response()->json([
                'success' => false,
                'message' => 'Place ID is required',
            ], 400);
        }

        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        $this->locationService->setTenantId($tenantId);

        $details = $this->locationService->getPlaceDetails($placeId);

        if (!$details) {
            return response()->json([
                'success' => false,
                'message' => 'Location details not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $details,
        ]);
    }

    /**
     * POST /api/v1/locations/distance
     * Calculate distance between two locations
     */
    public function distance(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
        ]);

        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        $this->locationService->setTenantId($tenantId);

        $distance = $this->locationService->getDistance(
            $validated['origin'],
            $validated['destination']
        );

        if (!$distance) {
            return response()->json([
                'success' => false,
                'message' => 'Could not calculate distance',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $distance,
        ]);
    }

    /**
     * POST /api/v1/locations/nearby
     * Search nearby places (hotels, restaurants, attractions)
     */
    public function nearby(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'type' => 'sometimes|string|max:50', // 'lodging', 'restaurant', 'tourist_attraction'
            'radius' => 'sometimes|integer|min:100|max:50000',
        ]);

        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        $this->locationService->setTenantId($tenantId);

        $results = $this->locationService->searchNearby(
            $validated['lat'],
            $validated['lng'],
            $validated['type'] ?? 'lodging',
            $validated['radius'] ?? 5000
        );

        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    }

    /**
     * GET /api/v1/locations/geocode
     * Forward geocode an address to coordinates and place_id
     */
    public function geocode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'address' => 'required|string|max:200',
            'language' => 'sometimes|string|max:10',
        ]);

        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        $this->locationService->setTenantId($tenantId);

        $result = $this->locationService->geocode(
            $validated['address'],
            [
                'language' => $validated['language'] ?? null,
            ]
        );

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Geocoding failed',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}
