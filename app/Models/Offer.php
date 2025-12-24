<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offer extends Model
{
    protected $fillable = ['tenant_id', 'created_by', 'title', 'slug', 'description', 'itinerary', 'start_date', 'end_date', 'duration_days', 'group_size', 'base_price', 'discount_percentage', 'final_price', 'includes', 'excludes', 'terms_and_conditions', 'status', 'views'];

    protected $casts = [
        'itinerary' => 'array',
        'includes' => 'array',
        'excludes' => 'array',
        'base_price' => 'decimal:2',
        'final_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
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
