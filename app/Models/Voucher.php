<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    protected $fillable = ['tenant_id', 'invoice_id', 'code', 'qr_code', 'qr_code_image', 'status', 'value', 'currency', 'valid_from', 'valid_until', 'recipient_name', 'recipient_email', 'issued_at', 'redeemed_at', 'redemption_notes'];

    protected $casts = [
        'value' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'issued_at' => 'datetime',
        'redeemed_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
