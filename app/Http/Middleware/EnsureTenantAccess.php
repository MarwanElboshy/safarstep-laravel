<?php

namespace App\Http\Middleware;

use App\Services\Tenant\TenantContext;
use Spatie\Permission\PermissionRegistrar;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EnsureTenantAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!app()->bound(TenantContext::class)) {
            // Try to derive from authenticated user to prevent loops
            if ($request->user()?->tenant_id) {
                $tenantId = (string) $request->user()->tenant_id;
                if ($request->hasSession()) {
                    $request->session()->put('tenant_id', $tenantId);
                }
                $context = new TenantContext(id: $tenantId);
                app()->instance(TenantContext::class, $context);
                app(PermissionRegistrar::class)->setPermissionsTeamId($tenantId);
            } else {
                if ($request->expectsJson()) {
                    throw new BadRequestHttpException('Tenant context is missing.');
                }
                return redirect()->route('login');
            }
        }

        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);
        $user = $request->user();

        if (!$user || !$tenant->id) {
            if ($request->expectsJson()) {
                throw new AccessDeniedHttpException('You are not authorized for this tenant.');
            }
            return redirect()->route('login');
        }

        // If mismatch, align tenant to the user's tenant automatically
        if ((string) $user->tenant_id !== (string) $tenant->id) {
            $resolvedId = (string) $user->tenant_id;
            if ($request->hasSession()) {
                $request->session()->put('tenant_id', $resolvedId);
            }
            $context = new TenantContext(id: $resolvedId);
            app()->instance(TenantContext::class, $context);
            app(PermissionRegistrar::class)->setPermissionsTeamId($resolvedId);
        }

        return $next($request);
    }
}
