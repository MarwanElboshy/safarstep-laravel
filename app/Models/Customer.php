<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    protected $fillable = ['tenant_id', 'first_name', 'last_name', 'email', 'phone', 'address', 'city', 'country', 'customer_type', 'loyalty_tier', 'total_bookings', 'total_spent', 'last_booking_date', 'status'];

    protected $casts = [
        'total_spent' => 'decimal:2',
        'last_booking_date' => 'date',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
