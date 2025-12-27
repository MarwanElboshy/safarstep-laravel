<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected string $guard_name = 'sanctum';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_id',
        'department_id',
        'status',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['total_bookings', 'confirmed_bookings', 'total_revenue', 'performance'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function tenants()
    {
        // In multi-tenant system, a user can belong to multiple tenants
        // For now, return single tenant as array for compatibility
        return $this->tenant ? [$this->tenant] : [];
    }

    /**
     * Get performance metrics (placeholder until bookings module is implemented)
     */
    public function getTotalBookingsAttribute(): int
    {
        // TODO: Calculate from bookings table when implemented
        return 0;
    }

    public function getConfirmedBookingsAttribute(): int
    {
        // TODO: Calculate from bookings table when implemented
        return 0;
    }

    public function getTotalRevenueAttribute(): float
    {
        // TODO: Calculate from bookings table when implemented
        return 0.0;
    }

    public function getPerformanceAttribute(): int
    {
        // TODO: Calculate performance score based on metrics
        return 0;
    }
}
