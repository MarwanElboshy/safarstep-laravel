<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\Tour;
use App\Models\Car;
use App\Models\Destination;
use App\Models\TenantHotel;
use Illuminate\Support\Collection;

class ResourceService
{
    /**
     * Search hotels filtered by tenant context, city and stars.
     */
    public function searchHotels(?string $tenantId, ?string $search, ?string $city, ?int $stars): Collection
    {
        $query = Hotel::query()
            ->with('destination')
            ->where('status', 'active');

        if ($city) {
            $query->whereHas('destination', function ($q) use ($city) {
                $q->where('city', $city);
            });
        }
        if ($stars) {
            $query->where('stars', $stars);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        // If tenant-aware catalogs existed, we'd scope here; currently hotels are global.
        $hotels = $query->orderBy('stars', 'desc')->orderBy('name')->limit(50)->get();

        return $hotels->map(function (Hotel $h) {
            return [
                'id' => $h->id,
                'name' => $h->name,
                'stars' => $h->stars,
                'base_price_per_night' => (float) $h->base_price_per_night,
                'address' => $h->address,
                'city' => $h->destination?->city,
                'country' => $h->destination?->country,
            ];
        });
    }

    /**
     * Search tours filtered by tenant context and city.
     */
    public function searchTours(?string $tenantId, ?string $search, ?string $city, ?string $type): Collection
    {
        $query = Tour::query()
            ->with('destination')
            ->where('status', 'active');

        if ($city) {
            $query->whereHas('destination', function ($q) use ($city) {
                $q->where('city', $city);
            });
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('guide_name', 'like', "%$search%");
            });
        }
        // Future: filter by type when Tour has a type column

        $tours = $query->orderBy('name')->limit(50)->get();

        return $tours->map(function (Tour $t) {
            return [
                'id' => $t->id,
                'name' => $t->name,
                'duration' => $this->computeDuration($t->start_time, $t->end_time),
                'price_per_person' => (float) $t->price_per_person,
                'capacity' => $t->capacity,
                'city' => $t->destination?->city,
                'country' => $t->destination?->country,
            ];
        });
    }

    /**
     * Search transport (cars) filtered by tenant context and city.
     */
    public function searchTransport(?string $tenantId, ?string $search, ?string $city, ?string $mode): Collection
    {
        $query = Car::query()
            ->with('destination')
            ->whereIn('status', ['available', 'active']);

        if ($city) {
            $query->whereHas('destination', function ($q) use ($city) {
                $q->where('city', $city);
            });
        }
        if ($mode) {
            $query->where('vehicle_type', $mode);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('license_plate', 'like', "%$search%")
                  ->orWhere('vehicle_type', 'like', "%$search%")
                  ->orWhereJsonContains('features', $search);
            });
        }

        $cars = $query->orderBy('name')->limit(50)->get();

        return $cars->map(function (Car $c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'mode' => $c->vehicle_type,
                'vehicle_type' => $c->vehicle_type,
                'capacity' => $c->capacity,
                'luggage_capacity' => $c->luggage_capacity,
                'daily_rate' => $c->daily_rate ? (float) $c->daily_rate : null,
                'features' => $c->features,
                'policies' => $c->policies,
                'city' => $c->destination?->city,
                'country' => $c->destination?->country,
            ];
        });
    }

    private function computeDuration(?string $start, ?string $end): ?string
    {
        if (!$start || !$end) return null;
        try {
            $s = new \DateTime($start);
            $e = new \DateTime($end);
            $diff = $e->getTimestamp() - $s->getTimestamp();
            $hours = max(0, (int) floor($diff / 3600));
            return $hours > 0 ? $hours . ' hours' : '—';
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Create or update a Hotel by place details and attach TenantHotel settings.
     */
    public function addHotelFromPlace(string $tenantId, array $payload): array
    {
        // Resolve or create destination by city + country
        $destination = Destination::firstOrCreate(
            [
                'city' => $payload['city'],
                'country' => $payload['country'],
            ],
            [
                'name' => $payload['city'] . ', ' . $payload['country'],
                'slug' => str($payload['city'] . '-' . $payload['country'])->slug('-'),
                'latitude' => $payload['latitude'],
                'longitude' => $payload['longitude'],
            ]
        );

        // Create or update hotel under destination
        $hotel = Hotel::updateOrCreate(
            [
                'destination_id' => $destination->id,
                'name' => $payload['name'],
            ],
            [
                'slug' => str($payload['name'].'-'.$destination->city)->slug('-'),
                'address' => $payload['address'],
                'latitude' => $payload['latitude'],
                'longitude' => $payload['longitude'],
                'stars' => $payload['stars'] ?? null,
                'base_price_per_night' => $payload['base_price_per_night'] ?? 0,
                'status' => 'active',
            ]
        );

        // Attach or update tenant-specific settings
        $tenantHotel = TenantHotel::updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'hotel_id' => $hotel->id,
            ],
            [
                'currency' => $payload['currency'],
                'base_price_per_night' => $payload['base_price_per_night'],
                'tax_rate' => $payload['tax_rate'] ?? 0,
                'extra_bed_price' => $payload['extra_bed_price'] ?? null,
                'meal_plan' => $payload['meal_plan'] ?? null,
                'room_types' => $payload['room_types'] ?? null,
                'status' => 'active',
            ]
        );

        return [
            'hotel' => [
                'id' => $hotel->id,
                'name' => $hotel->name,
                'address' => $hotel->address,
                'stars' => $hotel->stars,
                'city' => $destination->city,
                'country' => $destination->country,
            ],
            'tenantHotel' => [
                'tenant_id' => $tenantHotel->tenant_id,
                'hotel_id' => $tenantHotel->hotel_id,
                'currency' => $tenantHotel->currency,
                'base_price_per_night' => (float) $tenantHotel->base_price_per_night,
                'tax_rate' => (float) $tenantHotel->tax_rate,
                'extra_bed_price' => $tenantHotel->extra_bed_price ? (float) $tenantHotel->extra_bed_price : null,
                'meal_plan' => $tenantHotel->meal_plan,
                'room_types' => $tenantHotel->room_types,
            ],
        ];
    }

    /**
     * Combined search: Google Places (lodging near city) + mark if exists in DB and tenant has pricing
     */
    public function searchHotelsCombined(string $tenantId, ?string $search, ?string $city): Collection
    {
        $results = collect();
        try {
            $locationService = app(\App\Services\Location\LocationService::class);
            $locationService->setTenantId($tenantId);

            if (!$city) return collect();

            // Prefer text search when search term is provided; else use nearby search around city center
            $places = [];
            if ($search) {
                $places = $locationService->textSearchHotels($search, $city);
            } else {
                $geo = $locationService->geocode($city);
                if (!$geo || !$geo['lat'] || !$geo['lng']) {
                    return collect();
                }
                $places = $locationService->searchNearby((float)$geo['lat'], (float)$geo['lng'], 'lodging', 10000);
            }

            foreach (collect($places)->take(25) as $place) {
                $hotel = null;
                if (!empty($place['place_id'])) {
                    $hotel = \App\Models\Hotel::where('place_id', $place['place_id'])->first();
                }
                if (!$hotel) {
                    $hotel = \App\Models\Hotel::where('name', $place['name'] ?? '')
                        ->whereHas('destination', function ($q) use ($city) { $q->where('city', $city); })
                        ->first();
                }

                $tenantHotel = null;
                if ($hotel) {
                    $tenantHotel = \App\Models\TenantHotel::where('tenant_id', $tenantId)
                        ->where('hotel_id', $hotel->id)
                        ->first();
                }

                // Always try to get country
                $countryData = null;
                if (!$countryData) {
                    try {
                        $geoData = $locationService->geocode($city);
                        $countryData = $geoData['country'] ?? null;
                    } catch (\Throwable $ex) {
                        $countryData = null;
                    }
                }

                $results->push([
                    'id' => $hotel?->id,
                    'name' => $place['name'] ?? '',
                    'address' => $place['address'] ?? '',
                    'city' => $city,
                    'country' => $countryData,
                    'latitude' => $place['lat'] ?? null,
                    'longitude' => $place['lng'] ?? null,
                    'place_id' => $place['place_id'] ?? null,
                    'image_url' => $place['image_url'] ?? null,
                    'rating' => $place['rating'] ?? null,
                    'exists' => (bool) $hotel,
                    'tenant_has' => (bool) $tenantHotel,
                    'tenant_hotel_id' => $tenantHotel?->id,
                    'currency' => $tenantHotel?->currency,
                    'base_price_per_night' => $tenantHotel?->base_price_per_night ? (float)$tenantHotel->base_price_per_night : null,
                    'stars' => $hotel?->stars,
                ]);
            }
        } catch (\Throwable $e) {
            return collect();
        }

        return $results;
    }
}
