<?php

namespace App\Http\Middleware;

use App\Services\Tenant\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EnsureTenantAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!app()->bound(TenantContext::class)) {
            throw new BadRequestHttpException('Tenant context is missing.');
        }

        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);
        $user = $request->user();

        if (!$user || !$tenant->id || $user->tenant_id !== $tenant->id) {
            throw new AccessDeniedHttpException('You are not authorized for this tenant.');
        }

        return $next($request);
    }
}
