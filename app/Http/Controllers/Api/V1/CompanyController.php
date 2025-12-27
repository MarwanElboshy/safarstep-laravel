<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * GET /api/v1/companies/search?query=ABC
     */
    public function search(Request $request): JsonResponse
    {
        $q = trim($request->query('query', ''));
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        
        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'Tenant context required'], 400);
        }

        if ($q === '' || strlen($q) < 2) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $companies = Company::query()
            ->where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->where(function($w) use ($q) {
                $w->where('name', 'like', "%$q%")
                  ->orWhere('contact_person', 'like', "%$q%")
                  ->orWhere('email', 'like', "%$q%");
            })
            ->orderBy('name')
            ->limit(15)
            ->get(['id','name','contact_person','phone','email']);

        return response()->json(['success' => true, 'data' => $companies]);
    }

    /**
     * GET /api/v1/companies
     */
    public function index(Request $request): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        
        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'Tenant context required'], 400);
        }

        $companies = Company::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return response()->json(['success' => true, 'data' => $companies]);
    }

    /**
     * POST /api/v1/companies
     */
    public function store(Request $request): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        
        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'Tenant context required'], 400);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:100',
        ]);

        $company = Company::create([
            'tenant_id' => $tenantId,
            ...$validated,
            'status' => 'active',
        ]);

        return response()->json(['success' => true, 'data' => $company], 201);
    }

    /**
     * GET /api/v1/companies/{id}
     */
    public function show(Request $request, Company $company): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        
        if ($company->tenant_id !== $tenantId) {
            return response()->json(['success' => false, 'message' => 'Company not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $company]);
    }
}
