<?php

namespace App\Services\Tenant;

use Illuminate\Http\Request;

class TenantResolver
{
    public function resolve(Request $request): ?TenantContext
    {
        // Resolve tenant from header, querystring, or session (for web routes)
        $id = $request->header('X-Tenant-ID')
            ?: $request->query('tenant_id')
            ?: ($request->hasSession() ? $request->session()->get('tenant_id') : null);
        if (!$id) {
            return null;
        }

        return new TenantContext(id: (string) $id);
    }
}
