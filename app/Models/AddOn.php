<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddOn extends Model
{
    protected $table = 'add_ons';
    protected $fillable = ['name', 'slug', 'description', 'category', 'price', 'pricing_type', 'terms', 'status'];

    protected $casts = [
        'price' => 'decimal:2',
    ];
}
