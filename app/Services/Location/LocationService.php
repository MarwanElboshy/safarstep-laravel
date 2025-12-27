<?php

namespace App\Services\Location;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * LocationService - Google Maps integration for place search and details
 * Tenant-scoped, cached for performance
 */
class LocationService
{
    protected string $tenantId;
    protected ?string $googleApiKey = null;

    public function __construct()
    {
        // Lazy-load Google Maps API key only when needed
        // This prevents initialization errors during route compilation
    }

    /**
     * Get Google Maps API key (lazy initialization)
     */
    protected function getApiKey(): string
    {
        if ($this->googleApiKey === null) {
            // Config uses services.google.maps_key
            $this->googleApiKey = config('services.google.maps_key');
            if (!$this->googleApiKey) {
                throw new \Exception('Google Maps API key not configured in config/services.php. Add GOOGLE_MAPS_KEY to .env');
            }
        }
        return $this->googleApiKey;
    }

    public function setTenantId(string $tenantId): self
    {
        $this->tenantId = $tenantId;
        return $this;
    }

    /**
     * Autocomplete place search with photos (cities, countries, hotels, etc.)
     * Returns up to 20 results with photo references when available
     */
    public function autocomplete(string $input, array $options = []): array
    {
        try {
            $cacheKey = "gmap_autocomplete:{$this->tenantId}:{$input}";

            // Check cache first (5 min TTL)
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $params = [
                'input' => $input,
                'key' => $this->getApiKey(),
                'language' => $options['language'] ?? 'en',
            ];

            // Restrict to country when provided; otherwise no component restriction
            if (!empty($options['components'])) {
                $params['components'] = $options['components'];
            }

            $response = Http::get('https://maps.googleapis.com/maps/api/place/autocomplete/json', $params);

            if ($response->failed()) {
                Log::warning('Google Maps autocomplete failed', ['status' => $response->status()]);
                return [];
            }

            $data = $response->json();

            if ($data['status'] !== 'OK' && $data['status'] !== 'ZERO_RESULTS') {
                Log::warning('Google Maps API error', ['status' => $data['status']]);
                return [];
            }

            // Limit to 20 results
            $predictions = [];
            $places = array_slice($data['predictions'] ?? [], 0, 20);
            
            foreach ($places as $p) {
                $predictions[] = [
                    'place_id' => $p['place_id'],
                    'description' => $p['description'],
                    'main_text' => $p['structured_formatting']['main_text'] ?? '',
                    'secondary_text' => $p['structured_formatting']['secondary_text'] ?? '',
                ];
            }

            // Cache results
            Cache::put($cacheKey, $predictions, 300);

            Log::info('Google Maps autocomplete success', [
                'tenant_id' => $this->tenantId,
                'input' => $input,
                'results' => count($predictions),
            ]);

            return $predictions;
        } catch (\Exception $e) {
            Log::error('Google Maps autocomplete error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get detailed place information with photos, ratings, reviews
     */
    public function getPlaceDetails(string $placeId): ?array
    {
        try {
            $cacheKey = "gmap_details:{$this->tenantId}:{$placeId}";

            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $params = [
                'place_id' => $placeId,
                'key' => $this->getApiKey(),
                'fields' => 'formatted_address,geometry,address_components,name,types,photos,rating,user_ratings_total,reviews,opening_hours,formatted_phone_number,website,price_level,business_status',
            ];

            $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', $params);

            if ($response->failed()) {
                return null;
            }

            $data = $response->json();

            if ($data['status'] !== 'OK') {
                return null;
            }

            $result = $data['result'];

            // Process photos - Google Maps returns photo_reference which we convert to URLs
            $photos = [];
            if (!empty($result['photos'])) {
                foreach (array_slice($result['photos'], 0, 5) as $photo) {
                    $photos[] = [
                        'reference' => $photo['photo_reference'],
                        'url' => $this->getPhotoUrl($photo['photo_reference'], 800),
                        'thumbnail_url' => $this->getPhotoUrl($photo['photo_reference'], 400),
                        'width' => $photo['width'] ?? null,
                        'height' => $photo['height'] ?? null,
                    ];
                }
            }

            // Process reviews
            $reviews = [];
            if (!empty($result['reviews'])) {
                foreach (array_slice($result['reviews'], 0, 3) as $review) {
                    $reviews[] = [
                        'author' => $review['author_name'] ?? '',
                        'rating' => $review['rating'] ?? null,
                        'text' => $review['text'] ?? '',
                        'time' => $review['time'] ?? null,
                    ];
                }
            }

            $details = [
                'name' => $result['name'] ?? '',
                'address' => $result['formatted_address'] ?? '',
                'lat' => $result['geometry']['location']['lat'] ?? null,
                'lng' => $result['geometry']['location']['lng'] ?? null,
                'country' => $this->extractAddressComponent($result['address_components'], 'country'),
                'city' => $this->extractAddressComponent($result['address_components'], 'locality'),
                'place_id' => $placeId,
                'types' => $result['types'] ?? [],
                'photos' => $photos,
                'rating' => $result['rating'] ?? null,
                'user_ratings_total' => $result['user_ratings_total'] ?? 0,
                'reviews' => $reviews,
                'opening_hours' => $result['opening_hours']['weekday_text'] ?? [],
                'phone' => $result['formatted_phone_number'] ?? null,
                'website' => $result['website'] ?? null,
                'price_level' => $result['price_level'] ?? null,
                'business_status' => $result['business_status'] ?? null,
            ];

            Cache::put($cacheKey, $details, 3600); // 1 hour

            return $details;
        } catch (\Exception $e) {
            Log::error('Google Maps place details error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Generate Google Maps Photo URL from photo_reference
     */
    protected function getPhotoUrl(string $photoReference, int $maxWidth = 400): string
    {
        return sprintf(
            'https://maps.googleapis.com/maps/api/place/photo?maxwidth=%d&photo_reference=%s&key=%s',
            $maxWidth,
            $photoReference,
            $this->getApiKey()
        );
    }

    /**
     * Calculate distance between two locations
     */
    public function getDistance(string $origin, string $destination): ?array
    {
        try {
            $cacheKey = "gmap_distance:{$this->tenantId}:{$origin}:{$destination}";

            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $params = [
                'origins' => $origin,
                'destinations' => $destination,
                'key' => $this->getApiKey(),
                'mode' => 'driving',
            ];

            $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', $params);

            if ($response->failed()) {
                return null;
            }

            $data = $response->json();

            if ($data['status'] !== 'OK') {
                return null;
            }

            $element = $data['rows'][0]['elements'][0] ?? null;

            if (!$element || $element['status'] !== 'OK') {
                return null;
            }

            $distance = [
                'distance_km' => round($element['distance']['value'] / 1000, 2),
                'duration_hours' => round($element['duration']['value'] / 3600, 2),
                'text_distance' => $element['distance']['text'],
                'text_duration' => $element['duration']['text'],
            ];

            Cache::put($cacheKey, $distance, 86400); // 24 hours

            return $distance;
        } catch (\Exception $e) {
            Log::error('Google Maps distance matrix error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Search nearby places (hotels, restaurants, attractions)
     */
    public function searchNearby(float $lat, float $lng, string $type = 'lodging', int $radius = 5000): array
    {
        try {
            $cacheKey = "gmap_nearby:{$this->tenantId}:{$lat}:{$lng}:{$type}";

            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $params = [
                'location' => "{$lat},{$lng}",
                'type' => $type, // 'lodging', 'restaurant', 'tourist_attraction', etc.
                'radius' => $radius,
                'key' => $this->getApiKey(),
            ];

            $response = Http::get('https://maps.googleapis.com/maps/api/place/nearbysearch/json', $params);

            if ($response->failed()) {
                return [];
            }

            $data = $response->json();

            if ($data['status'] !== 'OK') {
                return [];
            }

            $results = array_map(function ($place) {
                $photoRef = null;
                if (!empty($place['photos']) && isset($place['photos'][0]['photo_reference'])) {
                    $photoRef = $place['photos'][0]['photo_reference'];
                }
                return [
                    'name' => $place['name'],
                    'address' => $place['vicinity'] ?? '',
                    'lat' => $place['geometry']['location']['lat'],
                    'lng' => $place['geometry']['location']['lng'],
                    'rating' => $place['rating'] ?? null,
                    'place_id' => $place['place_id'],
                    'types' => $place['types'] ?? [],
                    'image_url' => $photoRef ? $this->getPhotoUrl($photoRef, 400) : null,
                ];
            }, $data['results'] ?? []);

            Cache::put($cacheKey, $results, 3600);

            return $results;
        } catch (\Exception $e) {
            Log::error('Google Maps nearby search error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Text search hotels by query + city using Google Places Text Search API
     * Returns array of basic place info (name, address, lat, lng, place_id)
     */
    public function textSearchHotels(string $query, string $city, array $options = []): array
    {
        try {
            $cacheKey = 'gmap_textsearch_hotels:' . $this->tenantId . ':' . md5($query . '|' . $city . '|' . json_encode($options));
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $q = trim($query);
            $searchQuery = $q !== '' ? ($q . ' hotel in ' . $city) : ('hotels in ' . $city);

            $params = [
                'query' => $searchQuery,
                'key' => $this->getApiKey(),
                'language' => $options['language'] ?? 'en',
                // type filter may not always narrow results; still include
                'type' => 'lodging',
            ];

            $response = Http::get('https://maps.googleapis.com/maps/api/place/textsearch/json', $params);
            if ($response->failed()) {
                Log::warning('Google Places textsearch failed', ['status' => $response->status()]);
                return [];
            }

            $data = $response->json();
            if (!in_array($data['status'] ?? '', ['OK', 'ZERO_RESULTS'])) {
                Log::warning('Google Places textsearch API error', ['status' => $data['status'] ?? 'unknown']);
                return [];
            }

            $results = array_map(function ($place) {
                $photoRef = null;
                if (!empty($place['photos']) && isset($place['photos'][0]['photo_reference'])) {
                    $photoRef = $place['photos'][0]['photo_reference'];
                }
                return [
                    'name' => $place['name'] ?? '',
                    'address' => $place['formatted_address'] ?? ($place['vicinity'] ?? ''),
                    'lat' => $place['geometry']['location']['lat'] ?? null,
                    'lng' => $place['geometry']['location']['lng'] ?? null,
                    'rating' => $place['rating'] ?? null,
                    'place_id' => $place['place_id'] ?? null,
                    'types' => $place['types'] ?? [],
                    'image_url' => $photoRef ? $this->getPhotoUrl($photoRef, 400) : null,
                ];
            }, $data['results'] ?? []);

            Cache::put($cacheKey, $results, 1800); // 30 minutes
            return $results;
        } catch (\Exception $e) {
            Log::error('Google Places textsearch error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Forward geocode an address/place name to coordinates and place_id
     */
    public function geocode(string $address, array $options = []): ?array
    {
        try {
            $cacheKey = "gmap_geocode:{$this->tenantId}:" . md5($address . json_encode($options));

            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $params = [
                'address' => $address,
                'key' => $this->getApiKey(),
            ];

            if (isset($options['language'])) {
                $params['language'] = $options['language'];
            }

            if (isset($options['components'])) {
                $params['components'] = $options['components'];
            }

            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', $params);

            if ($response->failed()) {
                Log::warning('Google Maps geocode failed', ['status' => $response->status()]);
                return null;
            }

            $data = $response->json();

            if (!in_array($data['status'] ?? '', ['OK', 'ZERO_RESULTS'])) {
                Log::warning('Google Maps geocode API error', ['status' => $data['status'] ?? 'unknown']);
                return null;
            }

            $first = $data['results'][0] ?? null;
            if (!$first) {
                return null;
            }

            $details = [
                'formatted_address' => $first['formatted_address'] ?? '',
                'lat' => $first['geometry']['location']['lat'] ?? null,
                'lng' => $first['geometry']['location']['lng'] ?? null,
                'place_id' => $first['place_id'] ?? null,
                'types' => $first['types'] ?? [],
                'country' => $this->extractAddressComponent($first['address_components'] ?? [], 'country'),
                'city' => $this->extractAddressComponent($first['address_components'] ?? [], 'locality')
                    ?? $this->extractAddressComponent($first['address_components'] ?? [], 'administrative_area_level_1'),
                'address_components' => $first['address_components'] ?? [],
            ];

            Cache::put($cacheKey, $details, 3600);

            return $details;
        } catch (\Exception $e) {
            Log::error('Google Maps geocode error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    // ===== PRIVATE HELPERS =====

    protected function extractAddressComponent(array $components, string $type): ?string
    {
        $component = collect($components)
            ->first(fn($c) => in_array($type, $c['types'] ?? []));

        return $component['long_name'] ?? null;
    }
}
