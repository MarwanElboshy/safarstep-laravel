<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Tenant\TenantContext;
use Illuminate\Http\JsonResponse;

class TenantController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => []]);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json(['data' => ['id' => $id]]);
    }

    public function myTenants(?TenantContext $tenant = null): JsonResponse
    {
        return response()->json(['currentTenant' => $tenant?->id]);
    }
}
