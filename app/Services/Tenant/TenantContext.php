<?php

namespace App\Services\Tenant;

class TenantContext
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $primaryColor = null,
        public readonly ?string $secondaryColor = null,
        public readonly ?string $accentColor = null,
    ) {
    }
}
