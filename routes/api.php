<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TenantController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UserBulkController;
use App\Http\Controllers\Api\V1\DepartmentController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\RoleBulkController;
use App\Http\Controllers\Api\V1\PermissionController;
use App\Http\Controllers\Api\V1\OfferController;
use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\CountryController;
use App\Http\Controllers\Api\V1\CityController;
use App\Http\Controllers\Api\V1\AreaController;
use App\Http\Controllers\Api\V1\GeoController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\CompanyController;
use App\Http\Controllers\Api\V1\TransportationTypeController;
use App\Http\Controllers\Api\V1\TagController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// V1 Auth routes - no tenant context required
Route::prefix('v1/auth')
    // Use web stack (cookies + session) and disable CSRF for API auth endpoints
    ->middleware(['web'])
    ->withoutMiddleware([
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
    ])
    ->group(function () {
        Route::post('check-email', [AuthController::class, 'checkEmail'])->middleware('throttle:auth-general');
        Route::post('validate-credentials', [AuthController::class, 'validateCredentials'])->middleware('throttle:auth-general');
        Route::post('register', [AuthController::class, 'register'])->middleware('throttle:auth-general');
        Route::post('login', [AuthController::class, 'login'])->middleware('throttle:auth-login');
        
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
        });
    });

// Test endpoint (temporary)
Route::get('v1/test/users', function () {
    $users = \App\Models\User::where('tenant_id', 1)->get();
    return response()->json(['data' => $users]);
});

// V1 protected routes - tenant context required
Route::prefix('v1')
    ->middleware(['api', EnsureFrontendRequestsAreStateful::class, 'tenant', 'auth:sanctum', 'enforce-tenant'])
    ->group(function () {
        // Tenants
        Route::get('tenants', [TenantController::class, 'index']);
        Route::get('tenants/{id}', [TenantController::class, 'show']);
        Route::get('auth/tenants', [TenantController::class, 'myTenants']);

        Route::apiResource('users', UserController::class);
        
        // Roles (RBAC)
        Route::get('roles', [RoleController::class, 'index']);
        Route::get('roles/{role}/permissions', [RoleController::class, 'permissions']);
        Route::post('roles/{role}/permissions', [RoleController::class, 'syncPermissions']);
        Route::get('permissions', [PermissionController::class, 'index']);
        
        // Role bulk operations
        Route::prefix('roles/bulk')->group(function () {
            Route::post('add-permissions', [RoleBulkController::class, 'addPermissions']);
            Route::post('remove-permissions', [RoleBulkController::class, 'removePermissions']);
            Route::post('sync-permissions', [RoleBulkController::class, 'syncPermissions']);
        });
        
        // User bulk operations
        Route::prefix('users/bulk')->group(function () {
            Route::put('activate', [UserBulkController::class, 'activate']);
            Route::put('deactivate', [UserBulkController::class, 'deactivate']);
            Route::delete('delete', [UserBulkController::class, 'delete']);
            Route::put('assign-role', [UserBulkController::class, 'assignRole']);
            Route::post('role-change', [UserBulkController::class, 'roleChange']);
            Route::post('department-change', [UserBulkController::class, 'departmentChange']);
            Route::post('invite', [UserBulkController::class, 'invite']);
        });
        
        Route::apiResource('departments', DepartmentController::class);
        
        // Offers - use explicit routing to avoid name conflicts with web routes
        Route::get('offers', [OfferController::class, 'index']);
        Route::post('offers', [OfferController::class, 'store']);
        Route::get('offers/{offer}', [OfferController::class, 'show']);
        Route::put('offers/{offer}', [OfferController::class, 'update']);
        Route::patch('offers/{offer}', [OfferController::class, 'update']);
        Route::delete('offers/{offer}', [OfferController::class, 'destroy']);
        Route::post('offers/generate-from-prompt', [OfferController::class, 'generateFromPrompt']);
        Route::post('offers/{offer}/refine', [OfferController::class, 'refine']);
        Route::post('offers/{offer}/suggest-pricing', [OfferController::class, 'suggestPricing']);
        
        // Locations
        Route::get('locations/search', [LocationController::class, 'search']);
        Route::get('locations/nearby', [LocationController::class, 'searchNearby']);
        Route::post('locations/autocomplete', [LocationController::class, 'autocomplete']);
        Route::get('locations/details/{placeId}', [LocationController::class, 'details']);
        Route::post('locations/distance', [LocationController::class, 'distance']);
        Route::post('locations/nearby', [LocationController::class, 'nearby']);
        Route::get('locations/geocode', [LocationController::class, 'geocode']);

            // Tours
            Route::post('tours/search', [\App\Http\Controllers\Api\V1\ResourceController::class, 'tours']);

        // Countries and Cities
        Route::get('countries', [CountryController::class, 'index']);
        Route::get('countries/all', [CountryController::class, 'all']);
        Route::get('cities', [CityController::class, 'index']);
        Route::get('cities/search', [CityController::class, 'search']);
        Route::post('cities', [CityController::class, 'store']);
        Route::get('cities/by-place-id/{placeId}', [CityController::class, 'getByPlaceId']);
        Route::post('cities/by-country', [CityController::class, 'byCountry']);

        // Areas
        Route::get('areas', [AreaController::class, 'index']);
        Route::get('areas/search', [AreaController::class, 'search']);

        // Geo ingest endpoints
        Route::post('geo/ingest-place', [GeoController::class, 'ingestPlace']);
        Route::post('geo/ingest-area', [GeoController::class, 'ingestArea']);

        // Customers
        Route::get('customers/search', [CustomerController::class, 'search']);
        Route::post('customers', [CustomerController::class, 'store']);

        // Companies (B2B)
        Route::get('companies', [CompanyController::class, 'index']);
        Route::get('companies/search', [CompanyController::class, 'search']);
        Route::post('companies', [CompanyController::class, 'store']);
        Route::get('companies/{company}', [CompanyController::class, 'show']);

        // Transportation Types (Tenant-specific)
        Route::get('transportation-types', [TransportationTypeController::class, 'index']);
        Route::post('transportation-types', [TransportationTypeController::class, 'store']);
        Route::put('transportation-types/{transportationType}', [TransportationTypeController::class, 'update']);
        Route::delete('transportation-types/{transportationType}', [TransportationTypeController::class, 'destroy']);

        // Offer Features (Inclusions/Exclusions)
        Route::get('tags', [TagController::class, 'index']);
        Route::post('tags', [TagController::class, 'store']);
        Route::put('tags/{tag}', [TagController::class, 'update']);
        Route::delete('tags/{tag}', [TagController::class, 'destroy']);

        // Placeholder: add more routes per docs/ROADMAP.md phases under /api/v1/...

        // Resources (Hotels, Tours)
        Route::prefix('resources')->group(function () {
            Route::get('hotels', [\App\Http\Controllers\Api\V1\ResourceController::class, 'hotels']);
            Route::get('tours', [\App\Http\Controllers\Api\V1\ResourceController::class, 'tours']);
            Route::get('hotels/combined', [\App\Http\Controllers\Api\V1\ResourceController::class, 'hotelsCombined']);
            Route::get('transport', [\App\Http\Controllers\Api\V1\ResourceController::class, 'transport']);
        });

        // Tenant Hotels (create from Google selection)
        Route::post('tenant-hotels', [\App\Http\Controllers\Api\V1\TenantHotelController::class, 'store']);
    });
