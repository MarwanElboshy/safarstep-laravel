<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            // Travel Styles
            ['name' => 'Luxury', 'slug' => 'luxury', 'category' => 'travel_style'],
            ['name' => 'Adventure', 'slug' => 'adventure', 'category' => 'travel_style'],
            ['name' => 'Budget-Friendly', 'slug' => 'budget-friendly', 'category' => 'travel_style'],
            ['name' => 'Family-Friendly', 'slug' => 'family-friendly', 'category' => 'travel_style'],
            ['name' => 'Romantic', 'slug' => 'romantic', 'category' => 'travel_style'],
            
            // Experiences
            ['name' => 'Beach', 'slug' => 'beach', 'category' => 'experience'],
            ['name' => 'Cultural', 'slug' => 'cultural', 'category' => 'experience'],
            ['name' => 'Historical', 'slug' => 'historical', 'category' => 'experience'],
            ['name' => 'Shopping', 'slug' => 'shopping', 'category' => 'experience'],
            ['name' => 'Wellness', 'slug' => 'wellness', 'category' => 'experience'],
            
            // Group Types
            ['name' => 'Solo Traveler', 'slug' => 'solo-traveler', 'category' => 'group_type'],
            ['name' => 'Couples', 'slug' => 'couples', 'category' => 'group_type'],
            ['name' => 'Family Groups', 'slug' => 'family-groups', 'category' => 'group_type'],
            ['name' => 'Corporate', 'slug' => 'corporate', 'category' => 'group_type'],
            
            // Seasons
            ['name' => 'Summer', 'slug' => 'summer', 'category' => 'season'],
            ['name' => 'Winter', 'slug' => 'winter', 'category' => 'season'],
            ['name' => 'Spring', 'slug' => 'spring', 'category' => 'season'],
            ['name' => 'Fall', 'slug' => 'fall', 'category' => 'season'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['slug' => $tag['slug']],
                $tag
            );
        }
    }
}
