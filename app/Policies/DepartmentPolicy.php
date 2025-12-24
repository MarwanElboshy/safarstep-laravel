<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use App\Services\Tenant\TenantContext;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization;

    public function before(User $user): bool|null
    {
        if ($this->withinTenant($user) && $user->hasRole('super_admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $this->withinTenant($user) && $user->can('view_departments');
    }

    public function view(User $user, Department $department): bool
    {
        return $this->withinTenant($user, $department->tenant_id) && $user->can('view_departments');
    }

    public function create(User $user): bool
    {
        return $this->withinTenant($user) && $user->can('create_departments');
    }

    public function update(User $user, Department $department): bool
    {
        return $this->withinTenant($user, $department->tenant_id) && $user->can('edit_departments');
    }

    public function delete(User $user, Department $department): bool
    {
        return $this->withinTenant($user, $department->tenant_id) && $user->can('delete_departments');
    }

    private function withinTenant(User $user, ?string $resourceTenantId = null): bool
    {
        if (!app()->bound(TenantContext::class)) {
            return false;
        }

        $tenant = app(TenantContext::class);
        if (!$tenant->id) {
            return false;
        }

        if ($user->tenant_id !== $tenant->id) {
            return false;
        }

        if ($resourceTenantId !== null && $resourceTenantId !== $tenant->id) {
            return false;
        }

        return true;
    }
}
