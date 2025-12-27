<?php

namespace Database\Factories;

use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TourFactory extends Factory
{
    protected $model = Tour::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            'destination_id' => 1,
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(5),
            'description' => $this->faker->paragraph(),
            'guide_name' => $this->faker->name(),
            'capacity' => $this->faker->numberBetween(10, 40),
            'booked_seats' => 0,
            'price_per_person' => $this->faker->randomFloat(2, 20, 200),
            'start_time' => '09:00:00',
            'end_time' => '13:00:00',
            'itinerary' => json_encode([$this->faker->sentence()]),
            'includes' => json_encode(['Transport', 'Guide']),
            'status' => 'active',
        ];
    }
}
