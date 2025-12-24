<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['code' => 'USD', 'name' => 'US Dollar', 'rate' => 1.00],
            ['code' => 'EUR', 'name' => 'Euro', 'rate' => 0.92],
            ['code' => 'GBP', 'name' => 'British Pound', 'rate' => 0.79],
            ['code' => 'AED', 'name' => 'UAE Dirham', 'rate' => 3.67],
            ['code' => 'SAR', 'name' => 'Saudi Riyal', 'rate' => 3.75],
            ['code' => 'EGP', 'name' => 'Egyptian Pound', 'rate' => 30.50],
            ['code' => 'JOD', 'name' => 'Jordanian Dinar', 'rate' => 0.71],
            ['code' => 'KWD', 'name' => 'Kuwaiti Dinar', 'rate' => 0.31],
        ];

        foreach ($currencies as $currency) {
            Currency::firstOrCreate(
                ['code' => $currency['code']],
                $currency
            );
        }
    }
}

