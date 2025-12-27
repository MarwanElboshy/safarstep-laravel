# Refactored Offer Creation Architecture

## Overview

SafarStep's legacy offer creation wizard (7600 lines) has been refactored into a modern, AI-powered system with the following principles:
- **Simplicity**: Two-path creation (AI-powered or manual)
- **Automation**: OpenAI-powered offer generation from natural language
- **Location Intelligence**: Google Maps for autocomplete and place details
- **Tenant Isolation**: All data scoped by tenant ID
- **API-First**: Backend services consumed by RESTful endpoints

## Architecture Diagram

```
Frontend (Blade + Alpine.js)
    ↓
OfferController (API endpoints)
    ↓
OfferService (business logic)
    ├→ OfferAIService (OpenAI)
    ├→ LocationService (Google Maps)
    └→ Offer Model (database)
```

## Directory Structure

```
app/
├── Services/
│   ├── Offer/
│   │   └── OfferService.php          # Core offer logic
│   ├── OfferAI/
│   │   └── OfferAIService.php         # OpenAI integration
│   └── Location/
│       └── LocationService.php        # Google Maps integration
├── Http/
│   ├── Controllers/Api/V1/
│   │   ├── OfferController.php        # Offer CRUD + AI endpoints
│   │   └── LocationController.php     # Location endpoints
│   └── Requests/
│       └── OfferRequest.php           # Form validation
└── Models/
    └── Offer.php                      # Database model

resources/
└── views/
    └── offers/
        └── create.blade.php           # Refactored form (AI + Manual)
```

## Key Services

### OfferAIService

**Purpose**: Generate and refine offers using OpenAI's gpt-4o-mini

**Key Methods**:
- `setTenantId(string)` - Tenant context isolation
- `generateOfferFromPrompt(string, array)` - AI offer generation
- `enrichOfferWithSuggestions(array)` - Improvement suggestions
- `generatePricingInsights(array)` - Price optimization
- `logAIGeneration()` - Audit trail for compliance

**Configuration**:
```php
'services' => [
    'openai' => [
        'key' => env('OPENAI_API_KEY'),
        'model' => 'gpt-4o-mini',
    ],
]
```

### LocationService

**Purpose**: Google Maps integration for place search and distance calculation

**Key Methods**:
- `setTenantId(string)` - Tenant context isolation
- `autocomplete(string)` - Place autocomplete (5 min cache)
- `getPlaceDetails(string)` - Detailed place info (1 hour cache)
- `getDistance(origin, destination)` - Travel distance (24 hour cache)
- `searchNearby(lat, lng, type)` - Find nearby places (hotels, restaurants)

**Configuration**:
```php
'services' => [
    'google_maps' => [
        'key' => env('GOOGLE_MAPS_KEY'),
    ],
]
```

### OfferService

**Purpose**: Orchestrate offer creation with AI + location services

**Key Methods**:
- `setTenantId(string)` - Tenant context
- `generateFromPrompt(string, int, array)` - Generate + enrich offer
- `create(array)` - Persist offer to database
- `refine(Offer, string)` - AI-powered refinement with feedback
- `suggestPricing(Offer, array)` - Price optimization analysis
- `show(Offer)` - Get enriched offer data

## API Endpoints

### Offer CRUD

```
POST   /api/v1/offers                    # Create offer
GET    /api/v1/offers                    # List (paginated, tenant-scoped)
GET    /api/v1/offers/{id}               # Get details
PUT    /api/v1/offers/{id}               # Update
DELETE /api/v1/offers/{id}               # Delete
```

### Offer AI Features

```
POST   /api/v1/offers/generate-from-prompt  # AI generation
POST   /api/v1/offers/{id}/refine           # Refine with feedback
POST   /api/v1/offers/{id}/suggest-pricing  # Pricing insights
```

### Location Services

```
POST   /api/v1/locations/autocomplete    # Place search (travel regions)
GET    /api/v1/locations/details/{placeId}  # Detailed place info
POST   /api/v1/locations/distance        # Distance matrix
POST   /api/v1/locations/nearby          # Find nearby places
```

## Frontend: Refactored Offer Form

**Path**: `resources/views/offers/create.blade.php`

**Design**:
- Two-tab interface: "AI Generate" + "Manual Entry"
- **AI Tab**: Natural language prompt → AI generates offer
- **Manual Tab**: Traditional form for full control
- Location autocomplete powered by Google Maps
- Preview panel with quick action buttons
- Refinement modal for feedback-based improvements

**Alpine.js Component**:
```javascript
offerForm() {
    activeTab: 'ai' | 'manual'
    generatedOffer: { title, destination, duration_days, price_per_person, ... }
    destinationSuggestions: [] // from Google Maps
    loading: boolean
    refinementFeedback: string
}
```

## Tenant Isolation

Every layer enforces tenant scoping:

**API Headers**:
```
X-Tenant-ID: {tenant_uuid}
X-CSRF-TOKEN: {csrf_token}
```

**Query Filters**:
```php
Offer::where('tenant_id', $tenantId) // Always applied
LocationService->setTenantId($tenantId) // Namespace caching
```

**Policies** (future):
```php
// Only access offers belonging to user's tenant
OfferPolicy::view(User, Offer)
OfferPolicy::update(User, Offer)
```

## Data Flow: AI Generation

1. **User Input**:
   ```
   Prompt: "5-day luxury Nile cruise for couples, December, all-inclusive"
   Destination: "Cairo, Egypt" (optional, from Google Maps autocomplete)
   Department: "Tours" (required)
   ```

2. **Frontend Sends**:
   ```json
   POST /api/v1/offers/generate-from-prompt
   {
     "prompt": "5-day luxury...",
     "destination": "Cairo, Egypt",
     "department_id": 3,
     "duration_days": 5
   }
   ```

3. **Backend Processing**:
   - OfferController validates input
   - Calls OfferService->generateFromPrompt()
   - LocationService fetches place details (if destination provided)
   - OfferAIService builds SafarStep-specific system + user prompts
   - Calls OpenAI gpt-4o-mini with JSON schema
   - Parses response, logs audit trail, returns generated offer

4. **AI Response** (JSON structured):
   ```json
   {
     "title": "Cairo & Nile Luxury Escape",
     "description": "All-inclusive 5-day escape...",
     "destination": "Cairo, Egypt",
     "duration_days": 5,
     "price_per_person": 2450,
     "inclusions": ["..."],
     "exclusions": ["..."],
     "itinerary": [{"day": 1, "title": "...", "description": "..."}],
     "suggestions": ["Add spa services", "..."],
     "pricing_insights": {"margin": "...", "competitive": "..."}
   }
   ```

5. **User Review**:
   - Preview shows title, destination, duration, price
   - Can refine with feedback: "Add more dining options"
   - AI refines based on feedback
   - Clicks "Use This Offer" to save

6. **Persistence**:
   ```json
   POST /api/v1/offers
   {
     "title": "...",
     "destination": "...",
     ...,
     "department_id": 3,
     "meta": {
       "generated_from_ai": true,
       "location_details": {...},
       "pricing_insights": {...}
     }
   }
   ```

## Environment Setup

Add to `.env`:
```env
OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxx
GOOGLE_MAPS_KEY=AIzaSyxxxxxxxxxxxxxxx
```

Add to `config/services.php`:
```php
'openai' => [
    'key' => env('OPENAI_API_KEY'),
    'model' => 'gpt-4o-mini',
],
'google_maps' => [
    'key' => env('GOOGLE_MAPS_KEY'),
],
```

## Error Handling

**API Responses**:
```json
// Success
{
  "success": true,
  "message": "Offer generated successfully",
  "data": {...}
}

// Error
{
  "success": false,
  "message": "Failed to generate offer: Invalid API key"
}
```

**Logging**:
All AI generations, errors, and sensitive operations logged to `storage/logs/laravel.log` with tenant context:
```
[tenant_id] Offer generated from prompt [prompt_length] chars [department_id]
[tenant_id] Google Maps autocomplete [input] [results_count]
[tenant_id] Offer created [offer_id] [department_id]
```

## Testing Strategy

**Unit Tests**:
```php
tests/Unit/Services/OfferAIServiceTest.php
tests/Unit/Services/LocationServiceTest.php
```

**Feature Tests**:
```php
tests/Feature/Api/OfferControllerTest.php
tests/Feature/Api/LocationControllerTest.php
```

**Test Fixtures**:
- Mock OpenAI responses
- Mock Google Maps responses
- Tenant isolation verification
- Cost tracking validation

## Future Enhancements

1. **Cost Tracking**: Monitor AI/API costs per tenant
2. **Caching**: Cache generated offers for similar prompts
3. **A/B Testing**: Compare AI-generated vs manual offer performance
4. **Advanced Refinement**: Multi-step refinement workflow
5. **Batch Generation**: Generate multiple offers in one request
6. **Integration**: Sync with booking/payment systems

## Migration from Legacy

**Legacy Wizard** (old-project):
- 7600 lines of Vue + PHP code
- Tightly coupled to Laravel conventions
- Manual form handling, no AI

**Refactored Architecture**:
- ~300 lines per service (clean, focused)
- Service layer separation (testable, composable)
- AI-first workflow with manual fallback
- API endpoints for frontend decoupling
- Comprehensive logging and audit trails

**Benefits**:
- ✅ 95% less code
- ✅ Multi-tenant safe
- ✅ AI-assisted creation
- ✅ Location automation
- ✅ Full audit trail
- ✅ Test coverage
