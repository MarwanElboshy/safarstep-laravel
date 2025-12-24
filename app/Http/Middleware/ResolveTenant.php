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
        $tenant = $this->resolver->resolve($request);
        if ($tenant) {
            app()->instance(TenantContext::class, $tenant);
            app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
        } else {
            app(PermissionRegistrar::class)->setPermissionsTeamId(null);
        }

        return $next($request);
    }
}
