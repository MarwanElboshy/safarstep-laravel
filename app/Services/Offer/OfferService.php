<?php

namespace App\Services\Offer;

use App\Models\Offer;
use App\Services\Location\LocationService;
use App\Services\OfferAI\OfferAIService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * OfferService - Manages offer creation, refinement, and lifecycle
 * Integrates AI generation and location services
 * Tenant-scoped throughout
 */
class OfferService
{
    protected string $tenantId;

    public function __construct(
        protected OfferAIService $aiService,
        protected LocationService $locationService,
    ) {
    }

    public function setTenantId(string $tenantId): self
    {
        $this->tenantId = $tenantId;
        $this->aiService->setTenantId($tenantId);
        $this->locationService->setTenantId($tenantId);
        return $this;
    }

    /**
     * Generate offer from user prompt (AI-powered)
     * Returns: offer data ready for review/persistence
     */
    public function generateFromPrompt(
        string $prompt,
        ?int $departmentId = null,
        array $context = []
    ): array {
        try {
            // Build context with location info if provided
            if (isset($context['destination']) && $context['destination']) {
                $locationDetails = $this->locationService->getPlaceDetails($context['destination']);
                if ($locationDetails) {
                    $context['destination_details'] = $locationDetails;
                }
            }

            // Generate offer via AI
            $offerData = $this->aiService->generateOfferFromPrompt($prompt, $context);

            // Enhance with AI suggestions
            $suggestions = $this->aiService->enrichOfferWithSuggestions($offerData);
            $offerData['suggestions'] = $suggestions;

            // Generate pricing insights
            $pricingInsights = $this->aiService->generatePricingInsights($offerData);
            $offerData['pricing_insights'] = $pricingInsights;

            // Add metadata
            $offerData['draft'] = true; // Generated as draft for review
            $offerData['department_id'] = $departmentId;
            $offerData['tenant_id'] = $this->tenantId;
            $offerData['generated_at'] = now();

            Log::info('Offer generated from prompt', [
                'tenant_id' => $this->tenantId,
                'department_id' => $departmentId,
                'prompt_length' => strlen($prompt),
            ]);

            return $offerData;
        } catch (\Exception $e) {
            Log::error('Offer generation failed', [
                'tenant_id' => $this->tenantId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Create offer from generated/manual data
     */
    public function create(array $offerData): Offer
    {
        return DB::transaction(function () use ($offerData) {
            $offer = Offer::create([
                'tenant_id' => $this->tenantId,
                'department_id' => $offerData['department_id'],
                'title' => $offerData['title'],
                'description' => $offerData['description'] ?? null,
                'destination' => $offerData['destination'],
                'duration_days' => $offerData['duration_days'],
                'start_date' => $offerData['start_date'] ?? null,
                'end_date' => $offerData['end_date'] ?? null,
                'price_per_person' => $offerData['price_per_person'],
                'currency_id' => $offerData['currency_id'] ?? 1,
                'capacity' => $offerData['capacity'] ?? 50,
                'inclusions' => $offerData['inclusions'] ?? [],
                'exclusions' => $offerData['exclusions'] ?? [],
                'itinerary' => $offerData['itinerary'] ?? [],
                'status' => $offerData['status'] ?? 'active',
                'meta' => [
                    'generated_from_ai' => $offerData['draft'] ?? false,
                    'location_details' => $offerData['destination_details'] ?? null,
                    'pricing_insights' => $offerData['pricing_insights'] ?? null,
                ],
            ]);

            Log::info('Offer created', [
                'tenant_id' => $this->tenantId,
                'offer_id' => $offer->id,
                'department_id' => $offerData['department_id'],
            ]);

            return $offer;
        });
    }

    /**
     * Refine offer with user feedback
     */
    public function refine(Offer $offer, string $feedback): array
    {
        $this->authorize($offer);

        $refinedData = $this->aiService->enrichOfferWithSuggestions([
            'title' => $offer->title,
            'description' => $offer->description,
            'inclusions' => $offer->inclusions,
            'price' => $offer->price_per_person,
            'user_feedback' => $feedback,
        ]);

        Log::info('Offer refined', [
            'tenant_id' => $this->tenantId,
            'offer_id' => $offer->id,
            'feedback_length' => strlen($feedback),
        ]);

        return $refinedData;
    }

    /**
     * Suggest pricing adjustments based on AI analysis
     */
    public function suggestPricing(Offer $offer, array $context = []): array
    {
        $this->authorize($offer);

        $insights = $this->aiService->generatePricingInsights([
            'title' => $offer->title,
            'duration' => $offer->duration_days,
            'destination' => $offer->destination,
            'current_price' => $offer->price_per_person,
            'market_context' => $context,
        ]);

        return $insights;
    }

    /**
     * Get offer with all enriched data
     */
    public function show(Offer $offer): array
    {
        $this->authorize($offer);

        return [
            'id' => $offer->id,
            'title' => $offer->title,
            'description' => $offer->description,
            'destination' => $offer->destination,
            'duration_days' => $offer->duration_days,
            'price_per_person' => $offer->price_per_person,
            'currency' => $offer->currency?->code ?? 'USD',
            'capacity' => $offer->capacity,
            'inclusions' => $offer->inclusions,
            'exclusions' => $offer->exclusions,
            'itinerary' => $offer->itinerary,
            'status' => $offer->status,
            'location_details' => $offer->meta['location_details'] ?? null,
            'pricing_insights' => $offer->meta['pricing_insights'] ?? null,
            'generated_from_ai' => $offer->meta['generated_from_ai'] ?? false,
            'created_at' => $offer->created_at,
        ];
    }

    // ===== PRIVATE HELPERS =====

    protected function authorize(Offer $offer): void
    {
        if ($offer->tenant_id !== $this->tenantId) {
            throw new \Exception('Offer does not belong to this organization');
        }
    }
}
