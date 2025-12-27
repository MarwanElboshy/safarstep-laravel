<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UserStoreRequest;
use App\Http\Requests\Api\V1\UserUpdateRequest;
use App\Http\Resources\Api\V1\UserCollection;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use App\Services\Tenant\TenantContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of users with filtering and search
     */
    public function index(Request $request): JsonResponse
    {
        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        $query = User::query()
            ->where('tenant_id', $tenant->id)
            ->with(['roles', 'department']);

        // Search by name or email
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by role
        if ($request->has('role_id')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->input('role_id'));
            });
        }

        // Filter by department
        if ($request->has('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        // Sort
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate or get all
        if ($request->has('per_page')) {
            $users = $query->paginate($request->input('per_page', 50));
            return response()->json([
                'data' => UserResource::collection($users->items()),
                'meta' => [
                    'current_page' => $users->currentPage(),
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'last_page' => $users->lastPage(),
                ],
            ]);
        }

        $users = $query->get();
        return (new UserCollection($users))->response();
    }

    /**
     * Display the specified user
     */
    public function show(User $user): JsonResponse
    {
        $user->load(['roles', 'department']);
        return (new UserResource($user))->response();
    }

    /**
     * Store a newly created user
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'tenant_id' => $tenant->id,
            'department_id' => $data['department_id'] ?? null,
            'status' => $data['status'] ?? 'active',
        ]);

        // Assign role
        if (isset($data['role_id'])) {
            $user->roles()->attach($data['role_id']);
        }

        $user->load(['roles', 'department']);

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update the specified user
     */
    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        // Update role if provided
        if (isset($data['role_id'])) {
            $user->roles()->sync([$data['role_id']]);
        }

        $user->load(['roles', 'department']);

        return (new UserResource($user))->response();
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user): JsonResponse
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete your own account',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }
}
