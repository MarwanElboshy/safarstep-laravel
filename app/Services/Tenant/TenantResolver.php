<?php

namespace App\Services\Tenant;

use Illuminate\Http\Request;

class TenantResolver
{
    public function resolve(Request $request): ?TenantContext
    {
        // Minimal placeholder strategy: header or querystring
        $id = $request->header('X-Tenant-ID') ?: $request->query('tenant_id');
        if (!$id) {
            return null;
        }

        return new TenantContext(id: (string) $id);
    }
}
