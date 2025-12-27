# Phase 1 Quick Reference Guide

## API Endpoints

### Companies (B2B)

#### List All Companies
```http
GET /api/v1/companies
Headers:
  X-Tenant-ID: {tenant-uuid}
  X-CSRF-TOKEN: {csrf-token}
  
Response:
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Global Travel Agency",
      "contact_person": "Sarah Johnson",
      "phone": "+1-555-0101",
      "email": "sarah@globaltravel.com"
    }
  ]
}
```

#### Search Companies
```http
GET /api/v1/companies/search?query=global
Headers:
  X-Tenant-ID: {tenant-uuid}
  X-CSRF-TOKEN: {csrf-token}
  
Response:
{
  "success": true,
  "data": [/* matching companies */]
}
```

#### Create Company
```http
POST /api/v1/companies
Headers:
  X-Tenant-ID: {tenant-uuid}
  X-CSRF-TOKEN: {csrf-token}
  Content-Type: application/json
  
Body:
{
  "name": "New Travel Agency",
  "contact_person": "John Doe",
  "phone": "+1-555-0100",
  "email": "john@newagency.com",
  "country": "USA",
  "city": "Los Angeles",
  "address": "123 Main St",
  "tax_number": "US-123456"
}

Response:
{
  "success": true,
  "data": {/* created company */}
}
```

#### Get Company Details
```http
GET /api/v1/companies/{id}
Headers:
  X-Tenant-ID: {tenant-uuid}
  X-CSRF-TOKEN: {csrf-token}
  
Response:
{
  "success": true,
  "data": {/* company details */}
}
```

---

### Offer Features (Inclusions/Exclusions)

#### List Features
```http
GET /api/v1/tags?type=inclusion
GET /api/v1/tags?type=exclusion
GET /api/v1/tags (all types)

Headers:
  X-Tenant-ID: {tenant-uuid}
  X-CSRF-TOKEN: {csrf-token}
  
Response:
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Airport pickup and drop-off",
      "type": "inclusion",
      "is_global": true
    }
  ]
}
```

#### Create Feature
```http
POST /api/v1/tags
Headers:
  X-Tenant-ID: {tenant-uuid}
  X-CSRF-TOKEN: {csrf-token}
  Content-Type: application/json
  
Body:
{
  "name": "Hot air balloon ride",
  "type": "inclusion",
  "city_id": null,
  "country_id": null,
  "is_global": true
}

Response:
{
  "success": true,
  "data": {/* created feature */}
}
```

#### Update Feature
```http
PUT /api/v1/tags/{id}
Headers:
  X-Tenant-ID: {tenant-uuid}
  X-CSRF-TOKEN: {csrf-token}
  Content-Type: application/json
  
Body:
{
  "name": "Updated feature name",
  "is_global": false
}

Response:
{
  "success": true,
  "data": {/* updated feature */}
}
```

#### Delete Feature
```http
DELETE /api/v1/tags/{id}
Headers:
  X-Tenant-ID: {tenant-uuid}
  X-CSRF-TOKEN: {csrf-token}
  
Response:
{
  "success": true,
  "message": "Tag deleted"
}
```

---

## Frontend Integration

### B2B Company Selection

The company selection UI is conditionally shown based on client type:

```javascript
// In Alpine.js component
form: {
  client_type: 'b2c', // or 'b2b'
  company_id: null,
  ...
}

// Company search
async searchCompanies() {
  const resp = await fetch(`/api/v1/companies/search?query=${this.companySearch}`, {
    headers: {
      'X-Tenant-ID': window.appConfig.tenantId,
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    credentials: 'include'
  });
  const data = await resp.json();
  this.companyResults = data.data;
}

// Select company
selectCompany(company) {
  this.selectedCompany = company;
  this.form.company_id = company.id;
}
```

### Conditional Tab Visibility

Resource tabs automatically show/hide based on offer type:

```javascript
// Computed properties
get showAccommodationTab() {
  return ['complete', 'hotel'].includes(this.form.offer_type);
},
get showToursTab() {
  return ['complete', 'tours'].includes(this.form.offer_type);
},
get showTransportTab() {
  return ['complete', 'transport'].includes(this.form.offer_type);
},
get showFlightsTab() {
  return ['complete', 'tours', 'hotel', 'transport'].includes(this.form.offer_type);
},
get showAddonsTab() {
  return true; // Always visible
}
```

Usage in template:
```html
<button x-show="showAccommodationTab" @click="activeResourceTab = 'accommodation'">
  <i class="fas fa-hotel"></i> Accommodation
</button>
```

---

## Database Schema

### companies
- **Purpose**: Store B2B partner companies
- **Tenant Scoped**: Yes (tenant_id)
- **Indexes**: tenant_id + status

**Fields**:
- `id`: Primary key
- `tenant_id`: UUID foreign key to tenants
- `name`: Company name (required)
- `contact_person`: Primary contact name
- `phone`: Contact phone
- `email`: Contact email
- `country`: Company country
- `city`: Company city
- `address`: Full address
- `tax_number`: Tax registration number
- `status`: enum('active', 'inactive')

### offer_features
- **Purpose**: Store reusable inclusions and exclusions for offers
- **Tenant Scoped**: Yes (tenant_id)
- **Indexes**: tenant_id + type, tenant_id + city_id

**Fields**:
- `id`: Primary key
- `tenant_id`: UUID foreign key to tenants
- `name`: Feature description (required)
- `type`: enum('inclusion', 'exclusion')
- `city_id`: Optional city association
- `country_id`: Optional country association
- `is_global`: Boolean (true = applies to all locations)

---

## Testing Commands

### Seed Test Data
```bash
php artisan db:seed --class=CompanyAndFeatureSeeder
```

### Check Routes
```bash
php artisan route:list --path=api/v1/companies
php artisan route:list --path=api/v1/tags
```

### Verify Data
```bash
# Companies
mysql -u user -p database -e "SELECT * FROM companies"

# Offer Features
mysql -u user -p database -e "SELECT * FROM offer_features"
```

---

## UI Flow

### B2C Flow (Default)
1. User selects client_type = 'b2c'
2. Company selection section hidden (x-show="form.client_type === 'b2b'")
3. No company_id required in form submission

### B2B Flow
1. User selects client_type = 'b2b'
2. Company selection section becomes visible
3. User searches for company via autocomplete
4. User selects company from dropdown
5. Selected company displayed in card
6. form.company_id populated for submission
7. Optional: "Create New Company" opens modal (to be implemented)

### Offer Type Resource Tabs
- **Complete Package**: Shows all tabs (accommodation, tours, transport, flights, add-ons)
- **Tours Package**: Shows tours, flights, add-ons (hides accommodation, transport)
- **Hotel Package**: Shows accommodation, flights, add-ons (hides tours, transport)
- **Transport Package**: Shows transport, flights, add-ons (hides accommodation, tours)

---

## Validation Rules

### Company Creation
```php
'name' => 'required|string|max:255',
'contact_person' => 'nullable|string|max:255',
'phone' => 'nullable|string|max:50',
'email' => 'nullable|email|max:255',
'country' => 'nullable|string|max:100',
'city' => 'nullable|string|max:100',
'address' => 'nullable|string',
'tax_number' => 'nullable|string|max:100'
```

### Offer Feature Creation
```php
'name' => 'required|string|max:255',
'type' => 'required|in:inclusion,exclusion',
'city_id' => 'nullable|integer',
'country_id' => 'nullable|integer',
'is_global' => 'nullable|boolean'
```

---

## Next Implementation Steps

1. **Create Company Modal**: Add full form UI for creating new companies from offers page
2. **Link Resource APIs**: Replace mock data with real Hotels/Tours/Cars/Flights API calls
3. **Dynamic Features**: Load offer features from API instead of hardcoded suggestions
4. **Feature Management UI**: Admin page for managing inclusion/exclusion templates
5. **Calendar Distribution**: Interactive date picker for city assignments
6. **Smart Suggestions**: AI-powered hotel/tour recommendations based on context

---

## Troubleshooting

### Company Search Not Working
- Verify X-Tenant-ID header is set correctly
- Check CSRF token in meta tag
- Ensure credentials: 'include' in fetch options
- Verify user is authenticated

### Features Not Loading
- Check that type parameter is valid ('inclusion' or 'exclusion')
- Verify tenant context is set
- Ensure offer_features table has seeded data

### Conditional Tabs Not Showing
- Verify form.offer_type is set to valid value
- Check Alpine.js computed properties are defined
- Ensure x-show directives are properly bound

---

## Performance Considerations

### Companies Search
- Limited to 15 results for autocomplete
- Indexed on tenant_id + status for fast lookups
- Only searches active companies

### Offer Features
- Cached global features per tenant
- City/country filters use indexed queries
- Bulk load all features at wizard initialization

### Tenant Isolation
- All queries filtered by tenant_id
- Foreign key cascades on tenant deletion
- Indexes optimize tenant-scoped queries
