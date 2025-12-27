<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Location\GeoIngestService;
use App\Services\Location\LocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeoController extends Controller
{
    public function __construct(protected LocationService $locationService, protected GeoIngestService $geoIngest) {}

    /**
     * POST /api/v1/geo/ingest-place
     * Body: { place_id: string }
     * Returns internal IDs for country/city/destination
     */
    public function ingestPlace(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'place_id' => 'required|string|max:255'
        ]);

        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        $this->locationService->setTenantId($tenantId);

        $result = $this->geoIngest->ingestPlace($validated['place_id']);
        if (!$result) {
            return response()->json(['success' => false, 'message' => 'Unable to ingest place'], 400);
        }
        return response()->json(['success' => true, 'data' => $result]);
    }

    /**
     * POST /api/v1/geo/ingest-area
     * Body: { place_id: string, destination_id: int }
     * Returns internal IDs for area linked to destination
     */
    public function ingestArea(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'place_id' => 'required|string|max:255',
            'destination_id' => 'required|integer'
        ]);

        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        $this->locationService->setTenantId($tenantId);

        $result = $this->geoIngest->ingestArea($validated['place_id'], (int)$validated['destination_id']);
        if (!$result) {
            return response()->json(['success' => false, 'message' => 'Unable to ingest area'], 400);
        }
        return response()->json(['success' => true, 'data' => $result]);
    }
}
