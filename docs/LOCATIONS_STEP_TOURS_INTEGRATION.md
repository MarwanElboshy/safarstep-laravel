# Locations Step: Tours Integration

## Overview
Integrated tour management functionality directly into the Locations step (Step 2) of the offer creation workflow. Users can now select existing tours or create custom tours with Google Maps location selections for each day.

## What Changed

### 1. Frontend State Variables (JavaScript)
Added the following state variables to `public/assets/js/create-offer.js`:

```javascript
dayTours: [],                          // Array of {dayIndex, tours: [...]}
activeDayTab: 'locations',             // Tab toggle: 'locations' or 'tours'
createTourMode: false,                 // Tour creation mode toggle
newTourData: {                          // New tour being created
    name: '',
    duration: '',
    price: '',
    notes: '',
    locations: []
},
tourSearch: '',                         // Tour search input
tourSearchResults: [],                  // Tour search results
tourSearchLoading: false,              // Loading indicator
availableTours: [],                    // Tours available for selection
```

### 2. Frontend Functions (JavaScript)
Added the following functions to `public/assets/js/create-offer.js`:

#### Tour Data Management
- **`getDayTours(dayIndex)`** - Retrieve tours for a specific day
- **`addTourToDay(dayIndex, tour)`** - Add selected or created tour to a day
- **`removeTourFromDay(dayIndex, tourIndex)`** - Remove tour from a day

#### Tour Search & Creation
- **`searchTours(cityName)`** - POST request to `/api/v1/tours/search` to find tours by city
- **`initCreateTourMode(dayIndex)`** - Initialize custom tour creation interface
- **`addLocationToNewTour(location)`** - Add location to the tour being created
- **`removeLocationFromNewTour(locationIndex)`** - Remove location from tour creation
- **`saveTourWithLocations()`** - Save newly created tour with selected locations
- **`cancelCreateTour()`** - Cancel tour creation and reset form
- **`switchLocationTab(tab)`** - Switch between Locations and Tours tabs

### 3. Frontend UI (Blade Template)
Updated `resources/views/offers/create.blade.php` with:

#### Tab Navigation
Two tabs in the day expansion:
- **📍 Locations & Attractions** - Location selection (existing)
- **🎫 Tours** - Tour selection/creation (new)

#### Tours Tab Features

**Tour Search:**
- Search input to find existing tours by name
- Loading indicator during search
- Tour results display with:
  - Tour name, duration, price per person
  - Number of included locations
  - "Select" button to add tour to day

**Create Custom Tour:**
- "Create Custom Tour" button (purple)
- Form with fields:
  - **Tour Name** *(required)* - Name of the tour
  - **Duration** - e.g., "8 hours"
  - **Price per Person** - Cost for each guest
  - **Tour Description** - Details about the tour
  - **Add Locations to Tour** *(required)* - Location picker using same Google Maps search as locations tab
    - Reuses location search functionality
    - Shows search results with photos and place types
    - Selected locations display in a list
    - Can remove locations before saving
  - **Save Tour** and **Cancel** buttons

**Selected Tours Display:**
- Shows all tours assigned to current day
- Each tour card displays:
  - Tour name (bold)
  - Duration (⏱️ icon)
  - Price per person (💰 icon)
  - Number of locations (📍 icon)
  - Selected locations list (truncated)
  - Tour description/notes (📝 icon)
  - Remove button (red X)
- Empty state message if no tours selected

### 4. Backend Route
Added new API endpoint in `routes/api.php`:

```php
Route::post('tours/search', [\App\Http\Controllers\Api\V1\ResourceController::class, 'tours']);
```

This endpoint maps to the existing `ResourceController::tours()` method which already supports:
- Query parameter `search` - Search term
- Query parameter `city` - Filter by city
- Query parameter `type` - Filter by tour type

## Data Structure

### dayTours Array
```javascript
dayTours: [
    {
        dayIndex: 0,
        tours: [
            {
                id: 'tour-12345' or 'tour-{timestamp}' for custom tours,
                name: 'Petra Full Day Tour',
                duration: '8 hours',
                price: 150,
                notes: 'Tour description...',
                locations: [
                    { place_id, name, formatted_address, photo_url, types, ... }
                ],
                isCustom: true, // true for user-created tours
                created_at: '2025-...'
            }
        ]
    }
]
```

## User Workflow

### Selecting Existing Tours
1. In Locations step, click a day to expand it
2. Click the "🎫 Tours" tab
3. Search for tours in the city
4. Click "Select" on desired tour
5. Tour appears in "Selected Tours for This Day" section

### Creating Custom Tours
1. In Tours tab, click "Create Custom Tour" button
2. Enter tour details (name, duration, price, description)
3. Search for locations to include in the tour
4. Click "Add" on each location to include
5. Review selected locations
6. Click "Save Tour" to create and add to day
7. Custom tour appears in selected tours with all details

## Integration with Resources Step
- Tours created or selected in the Locations step are available for assignment in the Resources step
- Each tour retains its location data and pricing information
- Custom tours are stored per-offer with session persistence

## API Contract
The `/api/v1/tours/search` endpoint expects:

**Request:**
```json
{
    "search": "Petra",
    "city": "Amman",
    "type": "historical"
}
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": "tour-123",
            "name": "Petra Full Day Tour",
            "duration": "8 hours",
            "price": 150,
            "type": "historical",
            "capacity": 20,
            "locations": [...],
            "description": "..."
        }
    ]
}
```

## Features Implemented
✅ Tab-based interface for Locations and Tours
✅ Tour search functionality
✅ Custom tour creation with form validation
✅ Location picker for custom tours (reuses existing Google Maps integration)
✅ Selected tours display with full details
✅ Remove tours from day
✅ Cancel tour creation
✅ Tour persistence in dayTours state
✅ API endpoint for tour search
✅ Responsive UI with loading states

## Testing Checklist
- [ ] Tab switching works smoothly
- [ ] Tour search returns results correctly
- [ ] Creating a custom tour validates required fields
- [ ] Locations can be added/removed during tour creation
- [ ] Saving tour adds it to selected tours list
- [ ] Removing tour deletes it from selected tours
- [ ] Tours persist when switching between tabs
- [ ] Tours persist when expanding/collapsing days
- [ ] Multiple tours can be added to same day
- [ ] Tour data includes all location details

## Future Enhancements
- Save tours to database for reuse across offers
- Tour templates by destination
- Tour ratings and reviews
- Multi-day tour options
- Tour guide assignments
- Group size recommendations based on tour capacity
- Tour scheduling and availability
