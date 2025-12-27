# Offer Creation System - Setup Checklist

## 🚨 CRITICAL: Security First

### API Keys (EXPOSE ⚠️ - Must Rotate)

**Status**: Keys were shared in plain text in chat

**Action Items**:
- [ ] Go to https://platform.openai.com/api-keys and rotate OpenAI key
- [ ] Go to https://console.cloud.google.com and rotate Google Maps key
- [ ] Add new keys to `.env` file (never commit)
- [ ] Verify `.env` is in `.gitignore`
- [ ] Delete old keys from both services

### .env Configuration

```bash
# Add these to your .env file
OPENAI_API_KEY=sk-proj-YOUR_NEW_KEY_HERE
GOOGLE_MAPS_KEY=YOUR_NEW_KEY_HERE

# Verify these exist
APP_URL=http://localhost:8000
DB_DATABASE=safarstep_beta
```

## 📦 Dependencies

### Install Required Packages

```bash
# OpenAI PHP Client
composer require openai-php/client

# Google Maps (already in composer.json likely, verify)
composer require googlemaps/google-maps-services-php

# Verify installations
composer update
php artisan tinker
>>> new OpenAI\Client('test_key')
```

## 🔧 Configuration

### 1. Create/Update `config/services.php`

```php
<?php

return [
    // ... existing configs ...

    'openai' => [
        'key' => env('OPENAI_API_KEY'),
        'model' => 'gpt-4o-mini',
        'timeout' => 30,
    ],

    'google_maps' => [
        'key' => env('GOOGLE_MAPS_KEY'),
        'components' => 'country:EG|country:AE|country:TR', // Tourism regions
        'cache_ttl' => [
            'autocomplete' => 300,      // 5 minutes
            'details' => 3600,          // 1 hour
            'distance' => 86400,        // 24 hours
        ],
    ],
];
```

### 2. Verify Model Binding in `app/Models/Offer.php`

Ensure the `Offer` model exists and has these relationships:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    protected $fillable = [
        'tenant_id',
        'department_id',
        'title',
        'description',
        'destination',
        'duration_days',
        'start_date',
        'end_date',
        'price_per_person',
        'currency_id',
        'capacity',
        'inclusions',
        'exclusions',
        'itinerary',
        'status',
        'meta',
    ];

    protected $casts = [
        'inclusions' => 'json',
        'exclusions' => 'json',
        'itinerary' => 'json',
        'meta' => 'json',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Tenant scoping
    public function scopeForTenant($query, string $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
```

### 3. Verify `routes/api.php`

Routes are already registered (check section "Offers" and "Locations")

```bash
php artisan route:list | grep -E "offers|locations"
```

Expected output:
```
POST   /api/v1/offers
GET    /api/v1/offers
GET    /api/v1/offers/{offer}
PUT    /api/v1/offers/{offer}
DELETE /api/v1/offers/{offer}
POST   /api/v1/offers/generate-from-prompt
POST   /api/v1/offers/{offer}/refine
POST   /api/v1/offers/{offer}/suggest-pricing
POST   /api/v1/locations/autocomplete
GET    /api/v1/locations/details/{placeId}
POST   /api/v1/locations/distance
POST   /api/v1/locations/nearby
```

## 💾 Database

### 1. Create Offer Migration (if not exists)

```bash
php artisan make:migration create_offers_table

# Edit migration file
php artisan migrate
```

**Migration template** (if needed):
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('destination');
            $table->integer('duration_days');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('price_per_person', 10, 2);
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->onDelete('set null');
            $table->integer('capacity')->default(50);
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->json('itinerary')->nullable();
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
            $table->json('meta')->nullable(); // Generated from AI, pricing insights
            $table->timestamps();

            // Indexes for tenant queries
            $table->index('tenant_id');
            $table->index('department_id');
            $table->index('status');
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
```

### 2. Seed Sample Data (Optional)

```bash
php artisan tinker
>>> $tenant = Tenant::first();
>>> $dept = Department::where('tenant_id', $tenant->id)->first();
>>> Offer::create([
      'tenant_id' => $tenant->id,
      'department_id' => $dept->id,
      'title' => 'Test Offer',
      'destination' => 'Cairo, Egypt',
      'duration_days' => 5,
      'price_per_person' => 2500,
  ]);
```

## 🧪 Testing

### 1. Verify Routes Work

```bash
# Start dev server
php artisan serve

# In another terminal, test endpoints
curl -X POST http://localhost:8000/api/v1/offers/generate-from-prompt \
  -H "Content-Type: application/json" \
  -H "X-Tenant-ID: 1" \
  -H "X-CSRF-TOKEN: $(grep csrf-token .env)" \
  -d '{
    "prompt": "5-day Cairo tour for families",
    "department_id": 1
  }'
```

### 2. Test AI Integration

```bash
php artisan tinker
>>> app(OpenAI\Client::class)
>>> $service = app(\App\Services\OfferAI\OfferAIService::class);
>>> $service->setTenantId('1');
>>> $offer = $service->generateOfferFromPrompt('5-day Nile cruise');
>>> dump($offer);
```

### 3. Test Location Service

```bash
php artisan tinker
>>> $loc = app(\App\Services\Location\LocationService::class);
>>> $loc->setTenantId('1');
>>> $results = $loc->autocomplete('Cairo');
>>> dump($results);
```

## 📝 Create View/Blade Routes

### Add Web Routes (if not exists)

```php
// routes/web.php
Route::middleware(['auth', 'tenant'])->group(function () {
    Route::get('/offers', function () {
        return view('offers.index', [
            'offers' => auth()->user()->tenant->offers,
        ]);
    })->name('offers.index');

    Route::get('/offers/create', function () {
        return view('offers.create', [
            'departments' => auth()->user()->tenant->departments,
        ]);
    })->name('offers.create');

    Route::get('/offers/{offer}', function (Offer $offer) {
        return view('offers.show', ['offer' => $offer]);
    })->name('offers.show');
});
```

## ✅ Verification Checklist

- [ ] API keys rotated and stored in `.env`
- [ ] Dependencies installed: `composer require openai-php/client`
- [ ] `config/services.php` updated with OpenAI + Google Maps config
- [ ] Database migration created and migrated: `php artisan migrate`
- [ ] Routes registered: `php artisan route:list | grep offers`
- [ ] Services created: `OfferAIService`, `LocationService`, `OfferService`
- [ ] Controllers created: `OfferController`, `LocationController`
- [ ] Frontend form created: `resources/views/offers/create.blade.php`
- [ ] Form request created: `app/Http/Requests/OfferRequest.php`
- [ ] Model relationships verified: `Offer->tenant()`, `Offer->department()`
- [ ] Tenant middleware applied to all `/api/v1/offers` routes
- [ ] Test endpoints work: `curl /api/v1/offers`
- [ ] AI generation works: Call `POST /api/v1/offers/generate-from-prompt`
- [ ] Location autocomplete works: Call `POST /api/v1/locations/autocomplete`

## 🚀 Next Steps (If All Checks Pass)

1. Build frontend offer index/list view
2. Build frontend offer show/details view
3. Add offer deletion with confirmation modal
4. Create pricing insights dashboard
5. Add booking/payment integration
6. Write comprehensive feature tests
7. Deploy to production

## 📚 Documentation

- Full architecture: `docs/OFFER_CREATION_ARCHITECTURE.md`
- API docs: `docs/openapi.yaml` (update with new endpoints)
- Tenant + RBAC best practices: `.github/copilot-instructions.md`

## 🆘 Troubleshooting

**Error: `Call to undefined method LocationService`**
- Verify service is in `app/Services/Location/LocationService.php`
- Clear cache: `php artisan config:cache`

**Error: `OPENAI_API_KEY not configured`**
- Check `.env` has `OPENAI_API_KEY=sk-proj-...`
- Run `php artisan config:clear`
- Restart dev server

**Google Maps returns 0 results**
- Verify API key is enabled for Places API in Google Cloud Console
- Check if key has IP restrictions (whitelist your IP)
- Test in Google Maps Platform console

**Offer not saving to database**
- Verify `offers` table exists: `php artisan tinker` → `Schema::hasTable('offers')`
- Check `app/Models/Offer.php` fillable attributes
- Verify `tenant_id` in request header matches authenticated user's tenant
