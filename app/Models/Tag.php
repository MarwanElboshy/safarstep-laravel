<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'category'];

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_tags');
    }
}
