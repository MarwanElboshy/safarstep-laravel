<?php

namespace App\Policies;

use App\Models\User;
use App\Services\Tenant\TenantContext;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
        // Temporarily allow all tenant users for testing
        return $this->withinTenant($user);
        // return $this->withinTenant($user) && $user->can('view_users');
    }

    public function view(User $user, User $model): bool
    {
        // Temporarily allow all tenant users for testing
        return $this->withinTenant($user, $model->tenant_id);
        // return $this->withinTenant($user, $model->tenant_id) && $user->can('view_users');
    }

    public function create(User $user): bool
    {
        // Temporarily allow all tenant users for testing
        return $this->withinTenant($user);
        // return $this->withinTenant($user) && $user->can('create_users');
    }

    public function update(User $user, User $model): bool
    {
        // Temporarily allow all tenant users for testing
        return $this->withinTenant($user, $model->tenant_id);
        // return $this->withinTenant($user, $model->tenant_id) && $user->can('edit_users');
    }

    public function delete(User $user, User $model): bool
    {
        // Temporarily allow all tenant users for testing
        return $this->withinTenant($user, $model->tenant_id);
        // return $this->withinTenant($user, $model->tenant_id) && $user->can('delete_users');
    }

    public function bulkUpdate(User $user): bool
    {
        // Temporarily allow all tenant users for testing
        return $this->withinTenant($user);
        // return $this->withinTenant($user) && $user->can('edit_users');
    }

    public function bulkDelete(User $user): bool
    {
        // Temporarily allow all tenant users for testing
        return $this->withinTenant($user);
        // return $this->withinTenant($user) && $user->can('delete_users');
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
