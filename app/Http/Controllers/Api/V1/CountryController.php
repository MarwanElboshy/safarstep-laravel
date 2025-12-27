<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * GET /api/v1/countries
     * List all countries from the database
     */
    public function index(Request $request): JsonResponse
    {
        $countries = Country::query()
            ->select(['id', 'name', 'slug', 'iso2', 'iso3'])
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $countries,
        ]);
    }

    /**
     * GET /api/v1/countries/all
     * Lightweight list of countries as key-value pairs from DB
     */
    public function all(Request $request): JsonResponse
    {
        $countries = Country::query()
            ->select(['id', 'name', 'iso2', 'iso3'])
            ->orderBy('name')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'iso2' => $c->iso2,
                'iso3' => $c->iso3,
            ]);

        return response()->json([
            'success' => true,
            'data' => $countries,
        ]);
    }
}

