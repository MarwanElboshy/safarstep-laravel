<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TenantController;

// V1 Auth routes - no tenant context required
Route::prefix('v1/auth')
    ->middleware(['api'])
    ->group(function () {
        Route::post('check-email', [AuthController::class, 'checkEmail']);
        Route::post('validate-credentials', [AuthController::class, 'validateCredentials']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
        });
    });

// V1 protected routes - tenant context required
Route::prefix('v1')
    ->middleware(['api', 'tenant', 'auth:sanctum'])
    ->group(function () {
        // Tenants
        Route::get('tenants', [TenantController::class, 'index']);
        Route::get('tenants/{id}', [TenantController::class, 'show']);
        Route::get('auth/tenants', [TenantController::class, 'myTenants']);

        // Placeholder: add more routes per docs/ROADMAP.md phases under /api/v1/...
    });
