<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Tenant> */
class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        $name = $this->faker->company();
        return [
            'id' => Str::uuid()->toString(),
            'name' => $name,
            'slug' => Str::slug($name),
            'primary_color' => '#2A50BC',
            'secondary_color' => '#10B981',
            'accent_color' => '#2A50BC',
            'settings' => [
                'prefixes' => ['BK','INV','PAY','VCH','OFF'],
            ],
        ];
    }
}
