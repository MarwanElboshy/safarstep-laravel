<?php

namespace App\Http\Middleware;

use App\Services\Tenant\TenantContext;
use App\Services\Tenant\TenantResolver;
use Closure;
use Illuminate\Http\Request;

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
        }

        return $next($request);
    }
}
