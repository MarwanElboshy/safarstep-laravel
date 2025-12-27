<?php

namespace Database\Factories;

use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DestinationFactory extends Factory
{
    protected $model = Destination::class;

    public function definition(): array
    {
        $name = $this->faker->city();
        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(5),
            'description' => $this->faker->sentence(),
            'city' => $name,
            'country' => $this->faker->country(),
            'region' => $this->faker->state(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'highlights' => json_encode([$this->faker->sentence()]),
        ];
    }
}
