<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\TenantHotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantHotelController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'place_id' => ['required', 'string', 'max:128'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'stars' => ['nullable', 'integer', 'min:1', 'max:5'],
            'currency' => ['required', 'string', 'max:10'],
            'base_price_per_night' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0'],
            'extra_bed_price' => ['nullable', 'numeric', 'min:0'],
            'meal_plan' => ['nullable', 'string', 'max:50'],
            'room_types' => ['nullable', 'array'],
            'room_types.*.name' => ['required_with:room_types', 'string', 'max:100'],
            'room_types.*.capacity' => ['required_with:room_types', 'integer', 'min:1'],
            'room_types.*.base_price' => ['required_with:room_types', 'numeric', 'min:0'],
        ]);

        $tenantId = (string) ($request->header('X-Tenant-ID') ?? $request->user()?->tenant_id ?? '');
        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'Missing tenant context'], 400);
        }

        // Ensure destination exists
        $destination = Destination::firstOrCreate(
            ['city' => $validated['city'], 'country' => $validated['country']],
            [
                'name' => $validated['city'],
                'slug' => Str::slug($validated['city'] . '-' . $validated['country']),
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
            ]
        );

        // Try match by place_id first, fallback to name+address
        $hotel = Hotel::where('place_id', $validated['place_id'] ?? null)->first();
        if (!$hotel) {
            $hotel = Hotel::firstOrCreate(
                [
                    'destination_id' => $destination->id,
                    'name' => $validated['name'],
                    'address' => $validated['address'] ?? null,
                ],
                [
                    'slug' => Str::slug($validated['name'] . '-' . $validated['city']),
                    'place_id' => $validated['place_id'] ?? null,
                    'description' => null,
                    'stars' => $validated['stars'] ?? 0,
                    'latitude' => $validated['latitude'] ?? null,
                    'longitude' => $validated['longitude'] ?? null,
                    'amenities' => [],
                    'policies' => [],
                    'contact_phone' => null,
                    'contact_email' => null,
                    'base_price_per_night' => $validated['base_price_per_night'],
                    'status' => 'active',
                ]
            );
        } else {
            // Ensure destination and address are synced
            $hotel->destination_id = $destination->id;
            $hotel->address = $validated['address'] ?? $hotel->address;
            $hotel->stars = $validated['stars'] ?? $hotel->stars ?? 0;
            $hotel->latitude = $validated['latitude'] ?? $hotel->latitude;
            $hotel->longitude = $validated['longitude'] ?? $hotel->longitude;
            $hotel->save();
        }

        // Create or update tenant hotel link
        $tenantHotel = TenantHotel::updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'hotel_id' => $hotel->id,
            ],
            [
                'currency' => $validated['currency'],
                'base_price_per_night' => $validated['base_price_per_night'],
                'tax_rate' => $validated['tax_rate'] ?? 0,
                'extra_bed_price' => $validated['extra_bed_price'] ?? null,
                'meal_plan' => $validated['meal_plan'] ?? null,
                'room_types' => $validated['room_types'] ?? null,
                'status' => 'active',
            ]
        );

        return response()->json([
            'success' => true,
            'data' => [
                'hotel' => [
                    'id' => $hotel->id,
                    'name' => $hotel->name,
                    'stars' => $hotel->stars,
                    'address' => $hotel->address,
                    'city' => $destination->city,
                    'country' => $destination->country,
                    'latitude' => $hotel->latitude,
                    'longitude' => $hotel->longitude,
                ],
                'tenant_hotel' => [
                    'id' => $tenantHotel->id,
                    'currency' => $tenantHotel->currency,
                    'base_price_per_night' => (float) $tenantHotel->base_price_per_night,
                    'tax_rate' => (float) $tenantHotel->tax_rate,
                    'extra_bed_price' => $tenantHotel->extra_bed_price !== null ? (float) $tenantHotel->extra_bed_price : null,
                    'meal_plan' => $tenantHotel->meal_plan,
                    'room_types' => $tenantHotel->room_types,
                ],
            ],
        ]);
    }
}
