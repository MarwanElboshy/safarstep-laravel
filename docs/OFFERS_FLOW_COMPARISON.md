# SafarStep Offers Module - Implementation Comparison

## Current vs Required Implementation

### Overview
This document compares the current offer creation flow with the detailed requirements for B2B/B2C offer management in SafarStep.

---

## ✅ **What's Currently Implemented**

### Step 1: Basic Information
- ✅ Client search with autocomplete
- ✅ Client creation (name, phone required, email optional)
- ✅ Client type (B2C/B2B)
- ✅ Department selection
- ✅ Travel dates (start/end, auto-calculate duration)
- ✅ Travelers count (adults/children/infants)
- ✅ Primary destination
- ✅ Offer type selection (complete/tours/hotel/transport)
- ✅ Multi-city toggle
- ✅ Internal notes

### Step 2: City Distribution
- ✅ Multi-city selection
- ✅ City search via Google Maps
- ✅ Nights distribution per city
- ✅ Timeline visualization
- ✅ Date range per city
- ✅ Areas selection per city
- ✅ Google Places integration for areas

### Step 3: Day-by-Day Resources
- ✅ Accommodation tab
- ✅ Tours & Activities tab
- ✅ Transportation tab
- ✅ Flights tab
- ✅ Add-ons tab
- ✅ Resource counters

### Step 4: Financial Summary
- ✅ Category breakdown (accommodation/tours/transport/flights/add-ons)
- ✅ Purchase/Sale/Profit calculation
- ✅ Markup and optimization
- ✅ Per-category margins
- ✅ Total calculations

### Step 5: Inclusions & Exclusions
- ✅ Smart suggestions based on itinerary
- ✅ Select all/clear functions
- ✅ Custom items
- ✅ Categorized lists

### Step 6: Review & Finalize
- ✅ Validation checklist
- ✅ Summary preview
- ✅ Submit to API

---

## ❌ **What's Missing or Needs Updates**

### 1. Client Information Enhancements
**Missing:**
- ❌ Client nationality field
- ❌ Client country field
- ❌ Client source (Lead Source) field - should only appear when creating NEW client
- ❌ B2B company selection (when client_type is 'b2b')
- ❌ Popup modal for creating new client (currently inline)

**Required Changes:**
```javascript
// Add to customer creation form
{
    first_name: required,
    last_name: optional,
    phone: required,      // ✅ Already implemented
    email: optional,      // ✅ Already implemented
    nationality: optional, // ❌ Missing
    country: optional,     // ❌ Missing
    source: optional,      // ❌ Missing (only when creating new)
    customer_type: required // ✅ Already implemented
}

// For B2B
{
    company_id: required  // ❌ Missing - need Company model & selection
}
```

### 2. Offer Type Behavior
**Current:** All tabs show for all types
**Required:** Conditional tab visibility based on offer_type

| Offer Type | Show Tabs |
|------------|-----------|
| Complete Package | All tabs visible |
| Tours Package | Hide: Accommodation tab |
| Hotel Package | Hide: Tours tab (keep transfers), Car Rental |
| Transport Package | Hide: Accommodation tab |

**Required Logic:**
```javascript
computed: {
    showAccommodationTab() {
        return ['complete', 'hotel'].includes(this.form.offer_type);
    },
    showToursTab() {
        return ['complete', 'tours'].includes(this.form.offer_type);
    },
    showCarRentalTab() {
        return ['complete', 'transport'].includes(this.form.offer_type);
    }
}
```

### 3. City Distribution - Calendar UI
**Current:** Simple night input with timeline bar
**Required:** Interactive calendar-based UI

**Features Needed:**
- ❌ Full calendar view from start_date to end_date
- ❌ Click/drag to assign days to cities
- ❌ Color-coded days per city
- ❌ Visual date ranges
- ❌ Auto-calculate segments
- ❌ Support multiple stays in same city

**Database Structure:**
```sql
-- city_segments table (pivot)
offer_id, city_id, start_date, end_date, nights_count, sequence
```

### 4. Resources - Proper Linking System
**Current:** Mock resource selectors
**Required:** Link to actual resource modules

**For Each Resource Type:**
- ❌ Search/select from existing resources (Hotels/Tours/Cars/Flights/Add-ons modules)
- ❌ "Quick Create" option if resource doesn't exist
- ❌ Auto-fill purchase/sale prices from resource defaults
- ❌ Allow manual override in offer
- ❌ Option to "Save back to resource module"

**Example: Hotel Selection Flow:**
```
1. Select City → Filter hotels by city
2. Show hotel list with:
   - Star rating badge
   - "Most Used" badge
   - "Previously used with this client" badge
   - "Family Friendly" badge (from hotel metadata)
3. Select hotel → Show room types
4. Select room type → Show:
   - Capacity
   - Breakfast included?
   - View type
   - Default purchase/sale price
5. Enter number of rooms
6. System calculates: nights × rooms × price
```

### 5. Smart Hotel Room Suggestions
**Current:** No smart suggestions
**Required:** AI-powered room recommendations

**Logic:**
```javascript
recommendRooms(hotel, adults, children) {
    const totalPax = adults + children;
    const rooms = hotel.room_types;
    
    // Suggest based on capacity
    if (totalPax === 2) return rooms.filter(r => r.type === 'DBL');
    if (totalPax === 3) return rooms.filter(r => r.type === 'TRPL');
    if (totalPax === 4) return [
        { combo: '2×DBL', rooms: rooms.filter(r => r.type === 'DBL') },
        { combo: 'Apartment 2+1', rooms: rooms.filter(r => r.type === 'APT_2_1') }
    ];
    // ... etc
    
    // Badge: "Recommended for X people"
    // Warning (not blocking): "Capacity lower than travelers"
}
```

### 6. Tours - Private vs Group Logic
**Current:** Basic tour tab
**Required:** Proper tour type handling

**Private Tours:**
- Price per day for the vehicle (not per person)
- Must select vehicle type (Sedan/Vito/Sprinter)
- Calculate: days × price_per_day

**Group Tours:**
- Price per person
- Calculate: persons × price_per_person

**Transfers:**
- Separate category under Tours
- Price per transfer
- Types: Airport-Hotel, Hotel-Airport, City-to-City

### 7. Rent A Car - 24-Hour Calculation
**Current:** Missing rent-a-car section
**Required:** Full rent-a-car flow

**Fields:**
```javascript
{
    car_id: required,
    pickup_location: required,
    pickup_datetime: required,
    dropoff_location: required,
    dropoff_datetime: required,
    // Auto-calculate:
    rental_days: Math.ceil(hours / 24),
    total_purchase: rental_days × car.purchase_price_per_day,
    total_sale: rental_days × car.sale_price_per_day
}
```

**Example:**
- Pickup: 01/01 10:00 AM
- Dropoff: 05/01 12:00 PM
- Hours: 98 hours
- Days: Math.ceil(98/24) = 5 days charged

### 8. Inclusions/Exclusions - Tags System
**Current:** Hardcoded suggestions
**Required:** Dynamic Tags/Features system

**Database:**
```sql
CREATE TABLE tags (
    id, name, type ENUM('inclusion','exclusion'),
    city_id (nullable), country_id (nullable),
    is_global BOOLEAN
);
```

**UI Flow:**
1. Load tags filtered by offer's country/cities
2. Show as checkboxes (not hardcoded)
3. Allow adding custom tag with option:
   ✅ "Save this tag for future use"
4. If saved → Creates tag in `tags` table linked to city/country

### 9. Image Uploads with Titles
**Current:** No image upload section
**Required:** Image gallery with titles

**Fields per image:**
```javascript
{
    image_url: required,
    title: required, // e.g., "Outbound Ticket", "Return Ticket", "Hotel Exterior"
    sequence: auto
}
```

**Display in PDF:** Show images with their titles

### 10. Final Review Step
**Current:** Basic validation checklist
**Required:** Comprehensive accordion review

**Sections:**
- Client Info (name, phone, dates, travelers)
- City Stays (with timeline)
- Accommodation Summary (hotel, room, nights, total)
- Tours Summary (day-by-day list)
- Cars Summary (rent-a-car details)
- Flights Summary (routes, passengers)
- Add-ons Summary
- Financial Breakdown:
  - Total Purchase by category
  - Total Sale by category
  - Profit by category
  - Overall totals
- Inclusions/Exclusions lists
- Images preview

**Action Buttons:**
- "Edit" button per section → Go back to that step
- "Confirm & Generate PDF" → Final submit

---

## 📊 **Database Schema Updates Needed**

### 1. Update customers table
```sql
ALTER TABLE customers ADD COLUMN nationality VARCHAR(100) NULL;
ALTER TABLE customers ADD COLUMN country VARCHAR(100) NULL;
ALTER TABLE customers ADD COLUMN source VARCHAR(100) NULL; -- Lead source
```

### 2. Create companies table (for B2B)
```sql
CREATE TABLE companies (
    id BIGINT PRIMARY KEY,
    tenant_id BIGINT,
    name VARCHAR(255),
    contact_person VARCHAR(255),
    phone VARCHAR(50),
    email VARCHAR(255),
    country VARCHAR(100),
    city VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### 3. Create tags/features table
```sql
CREATE TABLE tags (
    id BIGINT PRIMARY KEY,
    tenant_id BIGINT,
    name VARCHAR(255),
    type ENUM('inclusion', 'exclusion'),
    city_id BIGINT NULL,
    country_id BIGINT NULL,
    is_global BOOLEAN DEFAULT false,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### 4. Update offers table
```sql
ALTER TABLE offers ADD COLUMN company_id BIGINT NULL; -- For B2B
ALTER TABLE offers ADD COLUMN currency_code VARCHAR(3) DEFAULT 'USD';
ALTER TABLE offers ADD COLUMN is_published BOOLEAN DEFAULT false;
ALTER TABLE offers ADD COLUMN pdf_url VARCHAR(500) NULL;
ALTER TABLE offers ADD COLUMN public_link VARCHAR(500) NULL;
```

### 5. Create offer_resources table
```sql
CREATE TABLE offer_resources (
    id BIGINT PRIMARY KEY,
    offer_id BIGINT,
    resource_type ENUM('hotel', 'tour', 'car', 'flight', 'addon', 'transfer'),
    resource_id BIGINT NULL, -- Links to hotels/tours/cars/etc
    custom_name VARCHAR(255) NULL, -- If manually entered
    quantity INT DEFAULT 1,
    days INT DEFAULT 1,
    purchase_price DECIMAL(10,2),
    sale_price DECIMAL(10,2),
    total_purchase DECIMAL(10,2),
    total_sale DECIMAL(10,2),
    profit DECIMAL(10,2),
    metadata JSON, -- Extra fields per resource type
    created_at TIMESTAMP
);
```

### 6. Create offer_city_segments table
```sql
CREATE TABLE offer_city_segments (
    id BIGINT PRIMARY KEY,
    offer_id BIGINT,
    city_id BIGINT,
    start_date DATE,
    end_date DATE,
    nights_count INT,
    sequence INT,
    created_at TIMESTAMP
);
```

### 7. Create offer_images table
```sql
CREATE TABLE offer_images (
    id BIGINT PRIMARY KEY,
    offer_id BIGINT,
    image_url VARCHAR(500),
    title VARCHAR(255),
    sequence INT,
    created_at TIMESTAMP
);
```

---

## 🎯 **Priority Implementation Plan**

### Phase 1: Critical Fixes (Week 1)
1. ✅ Customer phone required, email optional
2. ✅ Customer search/create flow
3. ❌ Add nationality, country, source fields to customer
4. ❌ Implement conditional tab visibility based on offer_type
5. ❌ Link resource selectors to actual modules (Hotels/Tours/Cars)

### Phase 2: Core Features (Week 2)
6. ❌ Calendar-based city distribution UI
7. ❌ Smart hotel/room suggestions
8. ❌ Rent-a-car with 24-hour calculation
9. ❌ Private vs Group tour logic
10. ❌ Transfer types

### Phase 3: Enhanced UX (Week 3)
11. ❌ Tags/Features system for inclusions/exclusions
12. ❌ Image uploads with titles
13. ❌ Comprehensive final review step
14. ❌ "Quick Create" modals for resources
15. ❌ B2B company selection

### Phase 4: Polish & PDF (Week 4)
16. ❌ PDF generation with all data
17. ❌ Public offer link generation
18. ❌ Purchase/Sale/Profit tracking per resource
19. ❌ Financial breakdown by category
20. ❌ Badge system (Most Used, Previously Used, etc.)

---

## 📝 **API Endpoints Needed**

```php
// Companies (B2B)
GET  /api/v1/companies
POST /api/v1/companies
GET  /api/v1/companies/{id}

// Tags/Features
GET  /api/v1/tags?city={city}&country={country}
POST /api/v1/tags
PUT  /api/v1/tags/{id}

// Hotels (existing but needs updates)
GET  /api/v1/hotels?city={city}&stars={stars}
GET  /api/v1/hotels/{id}/rooms

// Tours (existing but needs updates)
GET  /api/v1/tours?city={city}&type={private|group|transfer}
GET  /api/v1/tours/{id}

// Cars (new)
GET  /api/v1/cars?city={city}
GET  /api/v1/cars/{id}

// Flights (existing)
GET  /api/v1/flights?from={from}&to={to}&date={date}

// Add-ons (existing)
GET  /api/v1/addons

// Offer Resources
POST /api/v1/offers/{id}/resources
PUT  /api/v1/offers/{id}/resources/{resourceId}
DELETE /api/v1/offers/{id}/resources/{resourceId}

// Offer Images
POST /api/v1/offers/{id}/images
DELETE /api/v1/offers/{id}/images/{imageId}

// Offer Publishing
POST /api/v1/offers/{id}/publish
GET  /api/v1/offers/{id}/pdf
```

---

## ✅ **What's Working Well**

1. Customer autocomplete with tenant scoping ✅
2. Google Maps integration for cities/areas ✅
3. Multi-city support with visual timeline ✅
4. Pricing calculations framework ✅
5. Step-by-step wizard flow ✅
6. Alpine.js reactivity ✅

---

## 🚀 **Next Steps**

1. Review this comparison with the team
2. Prioritize missing features
3. Create tickets for each phase
4. Update database schema
5. Implement Phase 1 critical fixes
6. Build resource linking system
7. Create PDF generation service
8. Test B2B vs B2C flows separately

---

**Last Updated:** December 26, 2025
**Status:** Analysis Complete - Ready for Implementation
