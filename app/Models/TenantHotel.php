<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantHotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'hotel_id',
        'currency',
        'base_price_per_night',
        'tax_rate',
        'extra_bed_price',
        'meal_plan',
        'room_types',
        'status',
    ];

    protected $casts = [
        'room_types' => 'array',
        'base_price_per_night' => 'decimal:2',
        'extra_bed_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
