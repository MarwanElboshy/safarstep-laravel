<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * GET /api/v1/customers/search?query=John
     */
    public function search(Request $request): JsonResponse
    {
        $q = trim($request->query('query', ''));
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'Tenant context required'], 400);
        }

        if ($q === '' || strlen($q) < 1) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $customers = Customer::query()
            ->where('tenant_id', $tenantId)
            ->where(function($w) use ($q) {
                $w->where('first_name', 'like', "%$q%")
                  ->orWhere('last_name', 'like', "%$q%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%$q%"])
                  ->orWhere('email', 'like', "%$q%");
            })
            ->orderBy('last_booking_date', 'desc')
            ->limit(15)
            ->get(['id','first_name','last_name','email','phone','customer_type']);

        $data = $customers->map(fn($c) => [
            'id' => $c->id,
            'name' => trim($c->first_name.' '.$c->last_name),
            'email' => $c->email,
            'phone' => $c->phone,
            'type' => $c->customer_type,
        ]);

        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * POST /api/v1/customers
     * Body: { first_name, last_name, email?, phone?, customer_type }
     */
    public function store(Request $request): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        if (!$tenantId) {
            return response()->json(['success' => false, 'message' => 'Tenant context required'], 400);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'nationality' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'source' => 'nullable|string|max:100',
            'customer_type' => 'required|in:b2c,b2b',
            'company_id' => 'nullable|integer|exists:companies,id',
        ]);

        // Map customer_type from UI values to database enum values
        $customerTypeMap = [
            'b2c' => 'individual',
            'b2b' => 'corporate',
        ];

        $customer = Customer::create([
            'tenant_id' => $tenantId,
            'company_id' => $validated['company_id'] ?? null,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? '',
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'],
            'nationality' => $validated['nationality'] ?? null,
            'country' => $validated['country'] ?? null,
            'source' => $validated['source'] ?? null,
            'customer_type' => $customerTypeMap[$validated['customer_type']] ?? 'individual',
            'status' => 'active',
            'total_bookings' => 0,
            'total_spent' => 0,
        ]);

        return response()->json(['success' => true, 'data' => [
            'id' => $customer->id,
            'name' => trim($customer->first_name.' '.$customer->last_name),
            'email' => $customer->email,
            'phone' => $customer->phone,
            'type' => $customer->customer_type,
        ]], 201);
    }
}
