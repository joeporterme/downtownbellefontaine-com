<?php

namespace App\Services\Google;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://maps.googleapis.com/maps/api/geocode/json';

    public function __construct()
    {
        $this->apiKey = config('services.google.maps_api_key');
    }

    /**
     * Geocode an address and return structured address components with lat/lng.
     */
    public function geocode(string $address): ?array
    {
        try {
            $response = Http::get($this->baseUrl, [
                'address' => $address,
                'key' => $this->apiKey,
            ]);

            if (!$response->successful()) {
                Log::error('Google Geocoding API error', [
                    'status' => $response->status(),
                    'address' => $address,
                ]);
                return null;
            }

            $data = $response->json();

            if ($data['status'] !== 'OK' || empty($data['results'])) {
                Log::warning('Google Geocoding: No results', [
                    'status' => $data['status'],
                    'address' => $address,
                ]);
                return null;
            }

            return $this->parseResult($data['results'][0]);
        } catch (\Exception $e) {
            Log::error('Google Geocoding exception', [
                'message' => $e->getMessage(),
                'address' => $address,
            ]);
            return null;
        }
    }

    /**
     * Parse a geocoding result into a structured array.
     */
    protected function parseResult(array $result): array
    {
        $components = $this->parseAddressComponents($result['address_components'] ?? []);

        return [
            'formatted_address' => $result['formatted_address'] ?? null,
            'street_number' => $components['street_number'] ?? null,
            'street_name' => $components['route'] ?? null,
            'address' => trim(($components['street_number'] ?? '') . ' ' . ($components['route'] ?? '')),
            'city' => $components['locality'] ?? $components['sublocality'] ?? null,
            'state' => $components['administrative_area_level_1_short'] ?? null,
            'state_full' => $components['administrative_area_level_1'] ?? null,
            'zip' => $components['postal_code'] ?? null,
            'county' => $components['administrative_area_level_2'] ?? null,
            'country' => $components['country'] ?? null,
            'country_code' => $components['country_short'] ?? null,
            'latitude' => $result['geometry']['location']['lat'] ?? null,
            'longitude' => $result['geometry']['location']['lng'] ?? null,
            'place_id' => $result['place_id'] ?? null,
        ];
    }

    /**
     * Parse address components into a flat array.
     */
    protected function parseAddressComponents(array $components): array
    {
        $parsed = [];

        foreach ($components as $component) {
            foreach ($component['types'] as $type) {
                $parsed[$type] = $component['long_name'];
                $parsed[$type . '_short'] = $component['short_name'];
            }
        }

        return $parsed;
    }

    /**
     * Geocode with fallback - try the full address, then simpler versions.
     */
    public function geocodeWithFallback(string $address, ?string $city = null, ?string $state = null): ?array
    {
        // Try full address first
        $result = $this->geocode($address);
        if ($result) {
            return $result;
        }

        // Try with city and state appended
        if ($city && $state) {
            $result = $this->geocode("{$address}, {$city}, {$state}");
            if ($result) {
                return $result;
            }
        }

        // Try just city and state for a general location
        if ($city && $state) {
            $result = $this->geocode("{$city}, {$state}");
            if ($result) {
                return $result;
            }
        }

        return null;
    }
}
