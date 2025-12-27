# Turkish Tourism Test Data

## Overview
This document describes the test data seeded for Turkish tourism resources using the `TurkeyResourcesSeeder`.

## Seeded Resources Summary

### Hotels: 34
- **Star Ratings:** 2⭐ to 5⭐
- **Price Range:** $40 - $300 per night
- **Distribution:** 3-5 hotels per city
- **Features:**
  - Amenities based on star rating (more amenities for higher stars)
  - Turkish contact information (+90 212 555 XXXX)
  - Realistic policies (check-in/out, cancellation, pets)
  - Location coordinates

### Flights: 73
- **Airlines:** Turkish Airlines, Pegasus Airlines, SunExpress, AnadoluJet
- **Routes:** Inter-city connections between major Turkish cities
- **Price Range:** $140 - $450
- **Features:**
  - Realistic flight codes (e.g., TU123, PE456)
  - Multiple flights per route (2-3)
  - Varying departure times and durations

### Rental Cars: 51
- **Vehicle Types:** Economy, Compact, Sedan, SUV, Van, Luxury
- **Models:** Fiat Egea, Renault Clio, VW Golf, Toyota Corolla, Nissan Qashqai, etc.
- **Price Range:** $35 - $150 per day
- **Distribution:** 5-8 cars per city
- **Features:**
  - Turkish license plates (XX-XX-XXXX format)
  - Feature sets (GPS, AC, automatic transmission, etc.)
  - Passenger and luggage capacity

### Tours: 26
- **Types:**
  - Historical City Tours
  - Culinary Experiences
  - Boat Tours
  - Bazaar Shopping Tours
  - Hiking Adventures
- **Price Range:** $35 - $75 per person
- **Duration:** 2-6 hours
- **Distribution:** 3-4 tours per city
- **Features:**
  - Professional guides
  - Includes (entrance fees, meals, equipment)
  - Capacity and booking information

### Add-Ons: 13
- **Categories:**
  - Insurance (travel, medical, cancellation)
  - SIM cards and WiFi
  - Airport services (transfers, lounge access, fast-track)
  - Meals and activities
  - Adventure equipment
- **Pricing:** Per person, per day, or flat fee

## Cities Covered
1. **Adana** - 3 hotels, 5 cars, 3 tours
2. **Ankara** - 5 hotels, 6 cars, 3 tours
3. **Antalya** - 5 hotels, 6 cars, 4 tours
4. **Bursa** - 4 hotels, 6 cars, 3 tours
5. **Gaziantep** - 3 hotels, 8 cars, 3 tours
6. **Istanbul** - 5 hotels, 5 cars, 4 tours
7. **İzmir** - 5 hotels, 7 cars, 3 tours
8. **Konya** - 4 hotels, 8 cars, 3 tours

## Usage
To seed the database with this test data:
```bash
php artisan db:seed --class=TurkeyResourcesSeeder
```

To re-seed (clear and re-populate):
```bash
php artisan migrate:fresh --seed
php artisan db:seed --class=TurkeyResourcesSeeder
```

## Sample Data

### Sample Hotels
- Royal Suite Resort Adana - 5⭐ - $300/night
- Comfort Inn & Suites Adana - 4⭐ - $110/night
- Grand Palace Hotel Ankara - 5⭐ - $250/night
- Business Hotel Ankara - 4⭐ - $130/night

### Sample Flights
- TU630: Adana → Ankara - Turkish Airlines - $176
- SU839: Adana → Ankara - SunExpress - $172
- PE123: Istanbul → Antalya - Pegasus Airlines - $200

### Sample Cars
- Fiat Egea (economy) - $35/day
- Toyota Corolla (sedan) - $55/day
- Nissan Qashqai (SUV) - $75/day

## Testing in Offer Creation
1. Navigate to `/dashboard/offers/create`
2. Select Turkey as the primary destination
3. Add Turkish cities (Istanbul, Ankara, Antalya, etc.)
4. Resources will be available for selection:
   - Hotels with star ratings and pricing
   - Flights between cities
   - Rental cars by type
   - Tours and activities
   - Add-ons for enhanced experiences

## Schema Notes
- **Destinations:** Each Turkish city has its own destination record with country="Turkey"
- **Hotels/Cars/Tours:** Linked to city destinations via `destination_id`
- **Flights:** Use city names directly in `from_city` and `to_city` fields
- **Add-Ons:** Global resources not tied to specific destinations

## Realistic Features
- **Turkish Phone Numbers:** +90 212 555 XXXX format
- **License Plates:** XX-XX-XXXX (Turkish standard)
- **Airline Codes:** TU (Turkish Airlines), PE (Pegasus), SU (SunExpress), AN (AnadoluJet)
- **Star Ratings:** Amenities scale with hotel star rating
- **Pricing:** Reflects realistic Turkish tourism market rates
