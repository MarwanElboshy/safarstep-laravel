<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ResourceService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ResourceController extends Controller
{
    public function __construct(private ResourceService $service)
    {
    }

    public function hotels(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'stars' => ['nullable', 'integer', 'min:1', 'max:5'],
        ]);

        $tenantId = (string) ($request->header('X-Tenant-ID') ?? auth()->user()?->tenant_id ?? '');
        $hotels = $this->service->searchHotels(
            tenantId: $tenantId,
            search: $validated['search'] ?? null,
            city: $validated['city'] ?? null,
            stars: $validated['stars'] ?? null
        );

        return response()->json([
            'success' => true,
            'data' => $hotels,
        ]);
    }

    public function tours(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'string', 'max:50'],
        ]);

        $tenantId = (string) ($request->header('X-Tenant-ID') ?? auth()->user()?->tenant_id ?? '');
        $tours = $this->service->searchTours(
            tenantId: $tenantId,
            search: $validated['search'] ?? null,
            city: $validated['city'] ?? null,
            type: $validated['type'] ?? null,
        );

        return response()->json([
            'success' => true,
            'data' => $tours,
        ]);
    }

    public function transport(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'mode' => ['nullable', 'string', 'max:50'],
        ]);

        $tenantId = (string) ($request->header('X-Tenant-ID') ?? auth()->user()?->tenant_id ?? '');
        $transport = $this->service->searchTransport(
            tenantId: $tenantId,
            search: $validated['search'] ?? null,
            city: $validated['city'] ?? null,
            mode: $validated['mode'] ?? null,
        );

        return response()->json([
            'success' => true,
            'data' => $transport,
        ]);
    }

    /**
     * POST /api/v1/resources/hotels/add
     * Create or link a hotel from Google Place details and attach tenant-specific settings.
     */
    public function addHotelFromPlace(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'place_id' => ['required', 'string', 'max:200'],
            'name' => ['required', 'string', 'max:200'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'stars' => ['nullable', 'integer', 'min:1', 'max:5'],
            // Tenant-specific settings
            'currency' => ['required', 'string', 'max:10'],
            'base_price_per_night' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0'],
            'extra_bed_price' => ['nullable', 'numeric', 'min:0'],
            'meal_plan' => ['nullable', 'string', 'max:50'],
            'room_types' => ['nullable', 'array'],
            'room_types.*.name' => ['required_with:room_types', 'string', 'max:100'],
            'room_types.*.capacity' => ['required_with:room_types', 'integer', 'min:1', 'max:10'],
            'room_types.*.base_price' => ['required_with:room_types', 'numeric', 'min:0'],
        ]);

        $tenantId = (string) ($request->header('X-Tenant-ID') ?? auth()->user()?->tenant_id ?? '');
        if (empty($tenantId)) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant context is required',
            ], 400);
        }

        $result = $this->service->addHotelFromPlace($tenantId, $validated);

        return response()->json([
            'success' => true,
            'data' => $result,
        ], 201);
    }

    /**
     * Combined hotel search powered by Google Places, annotated with DB existence and tenant link status
     */
    public function hotelsCombined(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
        ]);

        $tenantId = (string) ($request->header('X-Tenant-ID') ?? auth()->user()?->tenant_id ?? '');
        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'Missing tenant context'], 400);
        }

        $hotels = $this->service->searchHotelsCombined(
            tenantId: $tenantId,
            search: $validated['search'] ?? null,
            city: $validated['city'] ?? null,
        );

        return response()->json([
            'success' => true,
            'data' => $hotels,
        ]);
    }
}
