<?php

namespace App\Services\Location;

use App\Models\Country;
use App\Models\City;
use App\Models\Destination;
use App\Models\Area;
use Illuminate\Support\Str;

class GeoIngestService
{
    public function __construct(protected LocationService $locationService) {}

    /**
     * Ingest a Google place_id and persist country/city/area records.
     * Returns array with internal IDs.
     */
    public function ingestPlace(string $placeId): ?array
    {
        $details = $this->locationService->getPlaceDetails($placeId);
        if (!$details) return null;

        $countryName = $details['country'] ?? null;
        $cityName = $details['city'] ?? null;

        if (!$countryName && !$cityName) return null;

        // Country
        $country = null;
        if ($countryName) {
            $country = Country::firstOrCreate(
                ['slug' => Str::slug($countryName)],
                ['name' => $countryName]
            );
        }

        // City
        $city = null;
        if ($cityName && $country) {
            $city = City::firstOrCreate(
                ['slug' => Str::slug($countryName.'-'.$cityName)],
                [
                    'country_id' => $country->id,
                    'name' => $cityName,
                    'latitude' => $details['lat'] ?? null,
                    'longitude' => $details['lng'] ?? null,
                ]
            );
        }

        // Destination (canonical record used across modules)
        $destination = null;
        if ($city) {
            $destination = Destination::firstOrCreate(
                ['slug' => Str::slug($countryName.'-'.$cityName)],
                [
                    'name' => $cityName,
                    'city' => $cityName,
                    'country' => $countryName,
                    'latitude' => $details['lat'] ?? null,
                    'longitude' => $details['lng'] ?? null,
                    'highlights' => [],
                ]
            );
        }

        return [
            'country' => $country ? ['id' => $country->id, 'name' => $country->name] : null,
            'city' => $city ? ['id' => $city->id, 'name' => $city->name, 'country_id' => $city->country_id] : null,
            'destination' => $destination ? ['id' => $destination->id, 'name' => $destination->name] : null,
        ];
    }

    /**
     * Ingest an Area linked to an existing Destination using a Google place_id
     */
    public function ingestArea(string $placeId, int $destinationId): ?array
    {
        $details = $this->locationService->getPlaceDetails($placeId);
        if (!$details) return null;

        $destination = Destination::find($destinationId);
        if (!$destination) return null;

        $name = $details['name'] ?? null;
        if (!$name) return null;

        $area = Area::firstOrCreate(
            ['slug' => Str::slug($destinationId.'-'.$name)],
            [
                'destination_id' => $destination->id,
                'name' => $name,
                'latitude' => $details['lat'] ?? null,
                'longitude' => $details['lng'] ?? null,
            ]
        );

        return [
            'area' => ['id' => $area->id, 'name' => $area->name, 'destination_id' => $area->destination_id],
            'destination' => ['id' => $destination->id, 'name' => $destination->name],
        ];
    }
}
