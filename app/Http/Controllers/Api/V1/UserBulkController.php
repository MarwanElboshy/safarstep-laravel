<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Tenant\TenantContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserBulkController extends Controller
{
    /**
     * Bulk activate users
     */
    public function activate(Request $request): JsonResponse
    {
        Gate::authorize('bulk-update', User::class);

        $validated = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'required|integer|exists:users,id',
        ]);

        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        $count = User::query()
            ->whereIn('id', $validated['user_ids'])
            ->where('tenant_id', $tenant->id)
            ->update(['status' => 'active']);

        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => "{$count} user(s) activated successfully",
        ]);
    }

    /**
     * Bulk deactivate users
     */
    public function deactivate(Request $request): JsonResponse
    {
        Gate::authorize('bulk-update', User::class);

        $validated = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'required|integer|exists:users,id',
        ]);

        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        $count = User::query()
            ->whereIn('id', $validated['user_ids'])
            ->where('tenant_id', $tenant->id)
            ->update(['status' => 'inactive']);

        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => "{$count} user(s) deactivated successfully",
        ]);
    }

    /**
     * Bulk delete users
     */
    public function delete(Request $request): JsonResponse
    {
        Gate::authorize('bulk-delete', User::class);

        $validated = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'required|integer|exists:users,id',
        ]);

        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        // Prevent deleting self
        $userIds = array_filter($validated['user_ids'], fn($id) => $id !== auth()->id());

        if (empty($userIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete your own account',
            ], 422);
        }

        $count = User::query()
            ->whereIn('id', $userIds)
            ->where('tenant_id', $tenant->id)
            ->delete();

        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => "{$count} user(s) deleted successfully",
        ]);
    }

    /**
     * Bulk assign role to users
     */
    public function assignRole(Request $request): JsonResponse
    {
        Gate::authorize('bulk-update', User::class);

        $validated = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'required|integer|exists:users,id',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        $users = User::query()
            ->whereIn('id', $validated['user_ids'])
            ->where('tenant_id', $tenant->id)
            ->get();

        foreach ($users as $user) {
            $user->roles()->sync([$validated['role_id']]);
        }

        return response()->json([
            'success' => true,
            'count' => $users->count(),
            'message' => "{$users->count()} user(s) assigned new role successfully",
        ]);
    }

    /**
     * Bulk role change with multiple modes (replace, add, remove)
     */
    public function roleChange(Request $request): JsonResponse
    {
        Gate::authorize('bulk-update', User::class);

        $validated = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'required|integer|exists:users,id',
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'required|integer',
            'mode' => 'required|in:replace,add,remove',
        ]);

        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        // Validate that all role_ids belong to the current tenant
        $validRoleIds = \Spatie\Permission\Models\Role::query()
            ->whereIn('id', $validated['role_ids'])
            ->where('tenant_id', $tenant->id)
            ->pluck('id')
            ->toArray();

        if (count($validRoleIds) !== count($validated['role_ids'])) {
            return response()->json([
                'success' => false,
                'message' => 'One or more roles do not belong to your tenant',
            ], 422);
        }

        $users = User::query()
            ->whereIn('id', $validated['user_ids'])
            ->where('tenant_id', $tenant->id)
            ->get();

        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No users found',
            ], 404);
        }

        foreach ($users as $user) {
            switch ($validated['mode']) {
                case 'replace':
                    $user->roles()->sync($validRoleIds);
                    break;
                case 'add':
                    $user->roles()->syncWithoutDetaching($validRoleIds);
                    break;
                case 'remove':
                    $user->roles()->detach($validRoleIds);
                    break;
            }
        }

        $action = match($validated['mode']) {
            'replace' => 'replaced',
            'add' => 'added',
            'remove' => 'removed',
        };

        return response()->json([
            'success' => true,
            'count' => $users->count(),
            'message' => "Roles {$action} for {$users->count()} user(s) successfully",
        ]);
    }

    /**
     * Bulk department change
     */
    public function departmentChange(Request $request): JsonResponse
    {
        Gate::authorize('bulk-update', User::class);

        $validated = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'required|integer|exists:users,id',
            'department_id' => 'required|integer|exists:departments,id',
        ]);

        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        $count = User::query()
            ->whereIn('id', $validated['user_ids'])
            ->where('tenant_id', $tenant->id)
            ->update(['department_id' => $validated['department_id']]);

        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => "{$count} user(s) moved to new department successfully",
        ]);
    }

    /**
     * Bulk invite users
     */
    public function invite(Request $request): JsonResponse
    {
        Gate::authorize('create', User::class);

        $validated = $request->validate([
            'emails' => 'required|array|min:1',
            'emails.*' => 'required|email',
            'role_id' => 'required|integer|exists:roles,id',
            'department_id' => 'nullable|integer|exists:departments,id',
            'send_welcome_email' => 'boolean',
        ]);

        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        $created = [];
        $existing = [];

        foreach ($validated['emails'] as $email) {
            // Check if user already exists
            $existingUser = User::where('email', $email)
                ->where('tenant_id', $tenant->id)
                ->first();

            if ($existingUser) {
                $existing[] = $email;
                continue;
            }

            // Create new user
            $user = User::create([
                'name' => explode('@', $email)[0],
                'email' => $email,
                'tenant_id' => $tenant->id,
                'department_id' => $validated['department_id'] ?? null,
                'status' => 'inactive', // User must activate via email
                'password' => bcrypt(bin2hex(random_bytes(16))), // Random password
            ]);

            // Assign role
            $user->roles()->sync([$validated['role_id']]);

            // TODO: Send welcome email if requested
            // if ($validated['send_welcome_email'] ?? true) {
            //     Mail::to($user)->send(new WelcomeEmail($user));
            // }

            $created[] = $email;
        }

        return response()->json([
            'success' => true,
            'created_count' => count($created),
            'existing_count' => count($existing),
            'created' => $created,
            'existing' => $existing,
            'message' => count($created) . " invitation(s) sent successfully",
        ]);
    }
}
