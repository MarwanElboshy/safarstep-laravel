<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Destination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CityController extends Controller
{
    /**
     * POST /api/v1/cities
     * Create a new city from Google Places data
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'country_name' => 'required|string|max:100',
            'place_id' => 'sometimes|string|max:255',
            'formatted_address' => 'sometimes|string|max:255',
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
            'place_data' => 'sometimes|array',
        ]);

        try {
            // Find or create the country
            $country = Country::where('name', $validated['country_name'])->first();
            if (!$country) {
                return response()->json([
                    'success' => false,
                    'message' => 'Country not found: ' . $validated['country_name'],
                ], 404);
            }

            // Check if city already exists by place_id or name+country
            if ($validated['place_id'] ?? null) {
                $existing = City::where('place_id', $validated['place_id'])->first();
                if ($existing) {
                    return response()->json([
                        'success' => true,
                        'data' => $existing,
                        'message' => 'City already exists',
                    ]);
                }
            }

            // Create new city
            $city = City::create([
                'country_id' => $country->id,
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'place_id' => $validated['place_id'] ?? null,
                'formatted_address' => $validated['formatted_address'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'place_data' => $validated['place_data'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'data' => $city,
                'message' => 'City created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create city: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/v1/cities/by-place-id/{placeId}
     * Get city by Google Place ID
     */
    public function getByPlaceId(string $placeId): JsonResponse
    {
        $city = City::where('place_id', $placeId)->first();
        
        if (!$city) {
            return response()->json([
                'success' => false,
                'message' => 'City not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $city,
        ]);
    }

    
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'country_id' => 'sometimes|integer',
            'country_name' => 'sometimes|string|max:100',
            'search' => 'sometimes|string|max:100',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ]);

        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        $hasTenantColumn = Schema::hasColumn('destinations', 'tenant_id');

        $query = City::query()->with('country');

        if (isset($validated['country_id'])) {
            $query->where('country_id', $validated['country_id']);
        }

        if (isset($validated['country_name'])) {
            $country = Country::where('name', $validated['country_name'])->first();
            if ($country) {
                $query->where('country_id', $country->id);
            } else {
                return response()->json(['success' => true, 'data' => []]);
            }
        }

        if (isset($validated['search'])) {
            $query->where('name', 'like', '%' . $validated['search'] . '%');
        }

        $cities = $query->orderBy('name')
            ->limit($validated['per_page'] ?? 50)
            ->get()
            ->map(function (City $c) use ($tenantId, $hasTenantColumn) {
                $dest = Destination::where('city', $c->name)
                    ->when($tenantId && $hasTenantColumn, fn($q) => $q->where('tenant_id', $tenantId))
                    ->first();

                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'latitude' => $c->latitude,
                    'longitude' => $c->longitude,
                    'country_id' => $c->country_id,
                    'country_name' => $c->country?->name,
                    'destination_id' => $dest?->id,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $cities,
        ]);
    }

    /**
     * GET /api/v1/cities/search
     * Search cities with fuzzy matching
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'query' => 'required|string|min:1|max:100',
            'country_id' => 'nullable|integer',
            'country_name' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'country_code' => 'nullable|string|max:3',
            'limit' => 'sometimes|integer|min:1|max:50',
        ]);

        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        $hasTenantColumn = Schema::hasColumn('destinations', 'tenant_id');

        $query = City::query()->with('country');

        $countryFilter = $validated['country_id'] ?? null;
        if (!$countryFilter) {
            $countryName = ($validated['country_name'] ?? $validated['country'] ?? null);
            $countryName = is_string($countryName) ? trim($countryName) : $countryName;
            $countryCode = $validated['country_code'] ?? null;
            if ($countryCode) {
                $countryFilter = Country::whereRaw('LOWER(iso2) = ?', [strtolower($countryCode)])
                    ->orWhereRaw('LOWER(iso3) = ?', [strtolower($countryCode)])
                    ->value('id');
            }
            if (!$countryFilter && $countryName) {
                $countryFilter = Country::where('name', $countryName)
                    ->orWhere('slug', Str::slug($countryName))
                    ->value('id');
            }
        }

        if ($countryFilter) {
            $query->where('country_id', $countryFilter);
        }

        $query->where('name', 'like', '%' . $validated['query'] . '%');

        $cities = $query->orderBy('name')
            ->limit($validated['limit'] ?? 20)
            ->get()
            ->map(function (City $c) use ($tenantId, $hasTenantColumn) {
                $dest = Destination::where('city', $c->name)
                    ->when($tenantId && $hasTenantColumn, fn($q) => $q->where('tenant_id', $tenantId))
                    ->first();

                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'latitude' => $c->latitude,
                    'longitude' => $c->longitude,
                    'country_id' => $c->country_id,
                    'country_name' => $c->country?->name,
                    'destination_id' => $dest?->id,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $cities,
        ]);
    }

    /**
     * POST /api/v1/cities/by-country
     * Get all cities for a given country
     */
    public function byCountry(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'country_name' => 'sometimes|string|max:100',
            'country_code' => 'sometimes|string|max:3',
            'country_id' => 'sometimes|integer',
        ]);

        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        $hasTenantColumn = Schema::hasColumn('destinations', 'tenant_id');

        $country = null;
        if (isset($validated['country_id'])) {
            $country = Country::find($validated['country_id']);
        }
        if (!$country && !empty($validated['country_code'])) {
            $code = strtolower($validated['country_code']);
            $country = Country::whereRaw('LOWER(iso2) = ?', [$code])
                ->orWhereRaw('LOWER(iso3) = ?', [$code])
                ->first();
        }
        if (!$country && !empty($validated['country_name'])) {
            $countryName = $validated['country_name'];
            $country = Country::where('name', $countryName)
                ->orWhere('slug', Str::slug($countryName))
                ->first();
        }
        if (!$country) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $cities = City::where('country_id', $country->id)
            ->orderBy('name')
            ->get()
            ->map(function (City $c) use ($tenantId, $hasTenantColumn) {
                $dest = Destination::where('city', $c->name)
                    ->when($tenantId && $hasTenantColumn, fn($q) => $q->where('tenant_id', $tenantId))
                    ->first();
                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'latitude' => $c->latitude,
                    'longitude' => $c->longitude,
                    'country_id' => $c->country_id,
                    'country_name' => $c->country?->name,
                    'destination_id' => $dest?->id,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $cities,
        ]);
    }
}
