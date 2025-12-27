<?php

namespace App\Http\Middleware;

use App\Services\Tenant\TenantContext;
use App\Services\Tenant\TenantResolver;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;

class ResolveTenant
{
    public function __construct(private TenantResolver $resolver)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        // Try to resolve tenant from header/query/session
        $tenant = $this->resolver->resolve($request);

        // If still missing, derive from authenticated user's tenant and persist to session
        if (!$tenant && $request->user()?->tenant_id) {
            $resolvedId = (string) $request->user()->tenant_id;
            if ($request->hasSession()) {
                $request->session()->put('tenant_id', $resolvedId);
            }
            $tenant = new TenantContext(id: $resolvedId);
        }

        if ($tenant) {
            app()->instance(TenantContext::class, $tenant);
            app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
        } else {
            app(PermissionRegistrar::class)->setPermissionsTeamId(null);
        }

        return $next($request);
    }
}
