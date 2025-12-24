<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
