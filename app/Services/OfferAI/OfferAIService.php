<?php

namespace App\Services\OfferAI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use OpenAI\Client;

/**
 * OfferAIService - Generates tourism offers using OpenAI
 * Tenant-scoped, audit-logged, cost-tracked
 */
class OfferAIService
{
    protected ?Client $openai = null;
    protected string $tenantId;

    public function __construct()
    {
        // Lazy-load OpenAI client only when needed
        // This prevents initialization errors during route compilation
    }

    /**
     * Get OpenAI client (lazy initialization)
     */
    protected function getClient(): Client
    {
        if ($this->openai === null) {
            $apiKey = config('services.openai.key');
            if (!$apiKey) {
                throw new \Exception('OpenAI API key not configured in config/services.php. Add OPENAI_API_KEY to .env');
            }
            $this->openai = \OpenAI::client($apiKey);
        }
        return $this->openai;
    }

    /**
     * Set tenant context for isolation and auditing
     */
    public function setTenantId(string $tenantId): self
    {
        $this->tenantId = $tenantId;
        return $this;
    }

    /**
     * Generate a complete offer from a user's text description
     *
     * @param string $prompt User's travel description (e.g., "5-day Egypt tour for family of 4, luxury")
     * @param array $context Optional: existing user data, preferences
     * @return array Parsed offer data structure
     */
    public function generateOfferFromPrompt(string $prompt, array $context = []): array
    {
        try {
            Log::info('OfferAI: Generating offer from prompt', [
                'tenant_id' => $this->tenantId,
                'prompt_length' => strlen($prompt),
            ]);

            $systemPrompt = $this->buildSystemPrompt($context);
            $userPrompt = $this->buildUserPrompt($prompt, $context);

            $response = $this->getClient()->chat()->create([
                'model' => 'gpt-4o-mini', // Fast, cost-effective
                'temperature' => 0.7,
                'max_tokens' => 2000,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
            ]);

            $content = $response->choices[0]->message->content;

            // Parse JSON from response
            $offer = $this->parseOfferJson($content);

            // Log generation for audit trail
            $this->logAIGeneration('generate_offer', $prompt, $offer);

            return $offer;
        } catch (\Exception $e) {
            Log::error('OfferAI: Generation failed', [
                'tenant_id' => $this->tenantId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Enhance an existing offer with AI suggestions
     */
    public function enrichOfferWithSuggestions(array $offerData): array
    {
        try {
            Log::info('OfferAI: Enriching offer with suggestions', [
                'tenant_id' => $this->tenantId,
            ]);

            $response = $this->getClient()->chat()->create([
                'model' => 'gpt-4o-mini',
                'temperature' => 0.6,
                'max_tokens' => 1500,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a tourism expert. Suggest improvements to an offer: better pacing, missing attractions, inclusions/exclusions, pricing insights. Return valid JSON.',
                    ],
                    [
                        'role' => 'user',
                        'content' => 'Enhance this offer: ' . json_encode($offerData),
                    ],
                ],
            ]);

            $suggestions = json_decode($response->choices[0]->message->content, true);

            $this->logAIGeneration('enrich_offer', json_encode($offerData), $suggestions ?? []);

            return $suggestions ?? [];
        } catch (\Exception $e) {
            Log::error('OfferAI: Enrichment failed', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Generate pricing insights for an offer
     */
    public function generatePricingInsights(array $offerData): array
    {
        try {
            $response = $this->getClient()->chat()->create([
                'model' => 'gpt-4o-mini',
                'temperature' => 0.5,
                'max_tokens' => 800,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a tourism pricing expert. Analyze offer and provide pricing strategies, competitor benchmarks, and margin recommendations. Return JSON.',
                    ],
                    [
                        'role' => 'user',
                        'content' => 'Analyze pricing for: ' . json_encode($offerData),
                    ],
                ],
            ]);

            return json_decode($response->choices[0]->message->content, true) ?? [];
        } catch (\Exception $e) {
            Log::error('OfferAI: Pricing analysis failed', ['error' => $e->getMessage()]);
            return [];
        }
    }

    // ===== PRIVATE HELPERS =====

    protected function buildSystemPrompt(array $context): string
    {
        return <<<PROMPT
You are an expert tourism offer builder for SafarStep, a multi-tenant SaaS platform.
Your job: Parse user travel descriptions and generate detailed, valid tourism offer structures.

Generate offers in strict JSON format with this structure:
{
  "basic": {
    "title": "Offer title",
    "description": "Brief overview",
    "country_id": null,
    "country_name": "Country",
    "cities": [{"id": null, "name": "City"}],
    "offer_type": "complete|tours|hotel|transport",
    "adults_count": 2,
    "children_count": 0,
    "children_ages": [],
    "arrival_date": "YYYY-MM-DD",
    "departure_date": "YYYY-MM-DD",
    "currency": "USD",
    "client_type": "b2c|b2b",
    "budget_level": "budget|mid-range|luxury"
  },
  "cityDistribution": {
    "segments": [{"city_id": null, "city_name": "City", "nights": 2, "start_date": "YYYY-MM-DD", "end_date": "YYYY-MM-DD"}]
  },
  "resources": {
    "accommodation": {"hotels": []},
    "tours": {"daily_schedule": [], "transfers": []},
    "rentals": {"cars": []},
    "flights": {"tickets": []},
    "addons": {"services": []}
  },
  "inclusions": {
    "included": ["Breakfast", "Airport transfer"],
    "excluded": ["Travel insurance", "Personal expenses"]
  },
  "financial": {
    "total_purchase": 5000,
    "total_sale": 7500,
    "total_profit": 2500,
    "profit_margin": 33.33
  }
}

Rules:
- Use realistic, current pricing.
- Match cities to real locations.
- Ensure dates are logically spaced.
- Include smart inclusions/exclusions based on package type.
- Always return valid JSON.
PROMPT;
    }

    protected function buildUserPrompt(string $userPrompt, array $context): string
    {
        $contextStr = $context ? json_encode($context, JSON_PRETTY_PRINT) : '';

        return <<<PROMPT
User Input: "$userPrompt"

Additional Context:
$contextStr

Generate a complete offer structure based on the user's request. Be smart about:
1. Matching cities to real countries
2. Suggesting realistic pricing tiers
3. Creating balanced itineraries
4. Allocating nights per city logically

Return ONLY valid JSON, no explanations.
PROMPT;
    }

    protected function parseOfferJson(string $content): array
    {
        // Extract JSON from response (may contain extra text)
        preg_match('/\{.*\}/s', $content, $matches);

        if (empty($matches)) {
            throw new \Exception('Failed to extract JSON from AI response');
        }

        $offer = json_decode($matches[0], true);

        if (!$offer) {
            throw new \Exception('Invalid JSON in AI response: ' . json_last_error_msg());
        }

        return $offer;
    }

    protected function logAIGeneration(string $action, string $input, array $output): void
    {
        try {
            // Optionally store in audit log for compliance/cost tracking
            Log::info("OfferAI: $action", [
                'tenant_id' => $this->tenantId,
                'input_length' => strlen($input),
                'output_keys' => array_keys($output),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log AI generation', ['error' => $e->getMessage()]);
        }
    }
}
