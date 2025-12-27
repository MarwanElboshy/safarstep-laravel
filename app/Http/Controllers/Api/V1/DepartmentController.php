<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Services\Tenant\TenantContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Department::class, 'department');
    }

    public function index(): JsonResponse
    {
        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        $departments = Department::query()
            ->where('tenant_id', $tenant->id)
            ->select(['id', 'tenant_id', 'name', 'parent_id', 'description'])
            ->withCount(['users' => function ($query) use ($tenant) {
                $query->where('tenant_id', $tenant->id);
            }])
            ->orderBy('name')
            ->get();

        // Transform users_count to member_count for frontend consistency
        $departments = $departments->map(function ($dept) {
            return [
                'id' => $dept->id,
                'tenant_id' => $dept->tenant_id,
                'name' => $dept->name,
                'parent_id' => $dept->parent_id,
                'description' => $dept->description,
                'member_count' => $dept->users_count ?? 0,
            ];
        });

        return response()->json(['data' => $departments]);
    }

    public function show(Department $department): JsonResponse
    {
        return response()->json(['data' => $department]);
    }

    public function store(Request $request): JsonResponse
    {
        /** @var TenantContext $tenant */
        $tenant = app(TenantContext::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $department = Department::create([
            'tenant_id' => $tenant->id,
            'name' => $data['name'],
            'parent_id' => $data['parent_id'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        return response()->json(['data' => $department], 201);
    }

    public function update(Request $request, Department $department): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $department->fill($data);
        $department->save();

        return response()->json(['data' => $department]);
    }

    public function destroy(Department $department): JsonResponse
    {
        $department->delete();

        return response()->json(['success' => true]);
    }
}
