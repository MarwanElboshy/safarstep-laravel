<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id', 'name', 'slug',
        'primary_color', 'secondary_color', 'accent_color',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    // Relationships
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function tenantHotels(): HasMany
    {
        return $this->hasMany(\App\Models\TenantHotel::class, 'tenant_id');
    }
}
