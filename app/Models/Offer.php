<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offer extends Model
{
    protected $fillable = [
        'tenant_id',
        'department_id',
        'created_by',
        'customer_id',
        'company_id',
        'title',
        'slug',
        'description',
        'destination',
        'country_id',
        'duration_days',
        'start_date',
        'end_date',
        'price_per_person',
        'currency_id',
        'capacity',
        'group_size',
        'base_price',
        'discount_percentage',
        'final_price',
        'image_url',
        'inclusions',
        'includes',
        'exclusions',
        'excludes',
        'itinerary',
        'terms_and_conditions',
        'status',
        'views',
        'meta',
    ];

    protected $casts = [
        'itinerary' => 'array',
        'inclusions' => 'array',
        'includes' => 'array',
        'exclusions' => 'array',
        'excludes' => 'array',
        'meta' => 'array',
        'price_per_person' => 'decimal:2',
        'base_price' => 'decimal:2',
        'final_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Tenant scoping
    public function scopeForTenant($query, string $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

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

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'offer_destinations')->withPivot('sequence', 'nights', 'activities');
    }

    public function addOns()
    {
        return $this->belongsToMany(AddOn::class, 'offer_add_ons')->withPivot('quantity', 'unit_price', 'total_price', 'is_mandatory');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'offer_tags');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
