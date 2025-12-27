<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use App\Services\Offer\OfferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function __construct(protected OfferService $offerService)
    {
    }

    /**
     * GET /api/v1/offers
     * List all offers for tenant
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'per_page' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1',
            'department_id' => 'sometimes|integer|exists:departments,id',
            'status' => 'sometimes|in:active,inactive,archived',
            'search' => 'sometimes|string|max:100',
        ]);

        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        $query = Offer::where('tenant_id', $tenantId);

        // Filter by department
        if (isset($validated['department_id'])) {
            $query->where('department_id', $validated['department_id']);
        }

        // Filter by status
        if (isset($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        // Search by title/destination
        if (isset($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('destination', 'like', "%{$search}%");
            });
        }

        $offers = $query->paginate($validated['per_page'] ?? 15);

        return response()->json([
            'success' => true,
            'data' => $offers->items(),
            'meta' => [
                'total' => $offers->total(),
                'per_page' => $offers->per_page(),
                'page' => $offers->current_page(),
                'last_page' => $offers->last_page(),
            ],
        ]);
    }

    /**
     * POST /api/v1/offers/generate-from-prompt
     * Generate offer from user prompt (AI-powered)
     */
    public function generateFromPrompt(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'prompt' => 'required|string|min:10|max:2000',
            'department_id' => 'sometimes|integer|exists:departments,id',
            'destination' => 'sometimes|string|max:255',
            'start_date' => 'sometimes|date',
            'duration_days' => 'sometimes|integer|min:1|max:180',
        ]);

        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        $this->offerService->setTenantId($tenantId);

        try {
            $context = array_filter([
                'destination' => $validated['destination'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'duration_days' => $validated['duration_days'] ?? null,
            ]);

            $generated = $this->offerService->generateFromPrompt(
                $validated['prompt'],
                $validated['department_id'] ?? null,
                $context
            );

            return response()->json([
                'success' => true,
                'message' => 'Offer generated successfully',
                'data' => $generated,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate offer: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/v1/offers
     * Create offer from data
     */
    public function store(OfferRequest $request): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        $this->offerService->setTenantId($tenantId);

        try {
            $offer = $this->offerService->create([
                ...$request->validated(),
                'tenant_id' => $tenantId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Offer created successfully',
                'data' => $offer,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create offer: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/v1/offers/{offer}
     * Get offer details
     */
    public function show(Request $request, Offer $offer): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        $this->offerService->setTenantId($tenantId);

        if ($offer->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Offer not found',
            ], 404);
        }

        try {
            $data = $this->offerService->show($offer);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    /**
     * PUT /api/v1/offers/{offer}
     * Update offer
     */
    public function update(OfferRequest $request, Offer $offer): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');

        if ($offer->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Offer not found',
            ], 404);
        }

        try {
            $offer->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Offer updated successfully',
                'data' => $offer,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update offer: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE /api/v1/offers/{offer}
     * Delete offer
     */
    public function destroy(Request $request, Offer $offer): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');

        if ($offer->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Offer not found',
            ], 404);
        }

        try {
            $offer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Offer deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete offer: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/v1/offers/{offer}/refine
     * Refine offer with user feedback
     */
    public function refine(Request $request, Offer $offer): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        $this->offerService->setTenantId($tenantId);

        $validated = $request->validate([
            'feedback' => 'required|string|min:5|max:1000',
        ]);

        if ($offer->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Offer not found',
            ], 404);
        }

        try {
            $refined = $this->offerService->refine($offer, $validated['feedback']);

            return response()->json([
                'success' => true,
                'message' => 'Offer refined with suggestions',
                'data' => $refined,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    /**
     * POST /api/v1/offers/{offer}/suggest-pricing
     * Get AI pricing suggestions
     */
    public function suggestPricing(Request $request, Offer $offer): JsonResponse
    {
        $tenantId = auth()->user()?->tenant_id ?? $request->header('X-Tenant-ID');
        $this->offerService->setTenantId($tenantId);

        if ($offer->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Offer not found',
            ], 404);
        }

        try {
            $suggestions = $this->offerService->suggestPricing($offer);

            return response()->json([
                'success' => true,
                'data' => $suggestions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }
}
