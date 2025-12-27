<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferFeature extends Model
{
    use HasFactory;

    protected $table = 'offer_features';

    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'city_id',
        'country_id',
        'is_global',
    ];

    protected $casts = [
        'is_global' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
