<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CountriesCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeds countries and cities from local JSON files
     * Data source: https://github.com/dr5hn/countries-states-cities-database
     */
    public function run(): void
    {
        $this->seedCountries();
        $this->seedCities();
    }

    /**
     * Seed countries from local JSON file
     */
    private function seedCountries(): void
    {
        echo "\n🌍 Seeding Countries...\n";
        
        $jsonPath = base_path('.old-project/countries-states-cities-database-master/json/countries.json');

        if (!file_exists($jsonPath)) {
            echo "❌ Countries file not found: {$jsonPath}\n";
            return;
        }

        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true);

        if (!isset($data['Data']) || !is_array($data['Data'])) {
            echo "❌ Invalid countries JSON structure\n";
            return;
        }

        $countries = $data['Data'];
        $count = 0;
        $skipped = 0;

        foreach ($countries as $countryData) {
            try {
                // Check if country already exists by ISO2 code
                $exists = Country::where('iso2', $countryData['code'] ?? '')
                    ->orWhere('iso3', $countryData['iso_3'] ?? '')
                    ->first();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                Country::create([
                    'name' => $countryData['name'] ?? 'Unknown',
                    'slug' => Str::slug($countryData['name'] ?? 'unknown'),
                    'iso2' => $countryData['code'] ?? null,
                    'iso3' => $countryData['iso_3'] ?? null,
                ]);

                $count++;

                // Progress indicator every 50 countries
                if ($count % 50 === 0) {
                    echo "  ✓ {$count} countries seeded...\n";
                }
            } catch (\Exception $e) {
                echo "  ⚠️  Error seeding country {$countryData['name']}: {$e->getMessage()}\n";
            }
        }

        echo "  ✅ Countries seeded: {$count} (skipped: {$skipped})\n";
    }

    /**
     * Seed cities from local JSON file
     */
    private function seedCities(): void
    {
        echo "\n🏙️  Seeding Cities...\n";
        
        $jsonPath = base_path('.old-project/countries-states-cities-database-master/json/cities.json');

        if (!file_exists($jsonPath)) {
            echo "❌ Cities file not found: {$jsonPath}\n";
            return;
        }

        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true);

        if (!isset($data['Data']) || !is_array($data['Data'])) {
            echo "❌ Invalid cities JSON structure\n";
            return;
        }

        $cities = $data['Data'];
        $count = 0;
        $skipped = 0;
        $notFound = 0;

        foreach ($cities as $cityData) {
            try {
                // Find country by ISO2 code
                $country = Country::where('iso2', $cityData['country_code'] ?? '')->first();

                if (!$country) {
                    $notFound++;
                    continue;
                }

                // Check if city already exists by name + country_id
                $exists = City::where('country_id', $country->id)
                    ->where('name', $cityData['name'] ?? '')
                    ->first();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                City::create([
                    'country_id' => $country->id,
                    'name' => $cityData['name'] ?? 'Unknown',
                    'slug' => Str::slug($cityData['name'] ?? 'unknown'),
                    'latitude' => $cityData['latitude'] ?? null,
                    'longitude' => $cityData['longitude'] ?? null,
                ]);

                $count++;

                // Progress indicator every 1000 cities
                if ($count % 1000 === 0) {
                    echo "  ✓ {$count} cities seeded...\n";
                }
            } catch (\Exception $e) {
                // Silently skip errors for individual cities
            }
        }

        echo "  ✅ Cities seeded: {$count} (skipped: {$skipped}, not found country: {$notFound})\n";
    }
}
