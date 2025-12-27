<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class HotelFactory extends Factory
{
    protected $model = Hotel::class;

    public function definition(): array
    {
        $name = $this->faker->company() . ' Hotel';
        return [
            'destination_id' => 1,
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(5),
            'description' => $this->faker->paragraph(),
            'stars' => $this->faker->numberBetween(3, 5),
            'address' => $this->faker->address(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'amenities' => json_encode([$this->faker->word()]),
            'policies' => json_encode(['checkin' => '2pm', 'checkout' => '12pm']),
            'contact_phone' => $this->faker->phoneNumber(),
            'contact_email' => $this->faker->safeEmail(),
            'base_price_per_night' => $this->faker->randomFloat(2, 50, 300),
            'status' => 'active',
        ];
    }
}
