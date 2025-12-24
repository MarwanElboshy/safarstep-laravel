# Navigation Mapping & Missing Functionalities

## Overview
This document maps the old project's navigation structure to the new v2 implementation and identifies missing functionalities that need to be built.

---

## ✅ Implemented Navigation (Dashboard Sidebar)

### Core Operations
- ✅ Dashboard (Home) - `/dashboard`
- ✅ Search - `/dashboard/search` 
- ✅ Notifications - `/dashboard/notifications`

### Business Operations
- ✅ Bookings - `/dashboard/bookings`
- ✅ Offers - `/dashboard/offers`
- ✅ Customers - `/dashboard/customers`
- ✅ Companies - `/dashboard/companies`

### Financial Management
- ✅ Finance Overview - `/dashboard/financial`
- ✅ Invoices - `/dashboard/financial/invoices`
- ✅ Payments - `/dashboard/financial/payments`
- ✅ Vouchers - `/dashboard/financial/vouchers`

### Resources
- ✅ Hotels - `/dashboard/resources/hotels`
- ✅ Flights - `/dashboard/resources/flights`
- ✅ Cars - `/dashboard/resources/cars`
- ✅ Tours - `/dashboard/resources/tours`
- ✅ Destinations - `/dashboard/resources/destinations`
- ✅ Add-ons - `/dashboard/resources/addon-services`

### Analytics & Reports
- ✅ Analytics - `/dashboard/analytics`
- ✅ Reports - `/dashboard/reports`

### Templates
- ✅ Templates - `/dashboard/templates`

### Administration
- ✅ Users - `/dashboard/users`
- ✅ Tenants (B2B Admin) - `/dashboard/b2b-admin`
- ✅ Subscriptions - `/dashboard/subscriptions`
- ✅ Settings - `/dashboard/settings`

---

## ⚠️ Missing Functionalities (Backend Implementation Required)

### 1. **Search Module** (Priority: HIGH)
**Old Project Structure:**
- All Search
- Bookings Search
- Offers Search
- Customers Search
- Resources Search

**Required Implementation:**
- [ ] Global search controller with elasticsearch/algolia
- [ ] Search endpoints for each module
- [ ] Search results pagination
- [ ] Search filters and facets
- [ ] Search history tracking

**Roadmap Reference:** Phase 7 (Search, Filtering & Pagination)

---

### 2. **Notifications System** (Priority: HIGH)
**Old Project Structure:**
- All Notifications
- Payment Alerts
- Booking Alerts
- System Notifications
- Email Templates

**Required Implementation:**
- [ ] Notification model and migrations
- [ ] Notification service with channels (email, SMS, in-app)
- [ ] Notification preferences per user
- [ ] Real-time notification broadcasting (WebSockets)
- [ ] Notification templates management
- [ ] Mark as read/unread functionality

**Roadmap Reference:** Phase 4 (Notification System)

---

### 3. **Bookings Module** (Priority: CRITICAL)
**Old Project Structure:**
- All Bookings
- Create Booking
- Pending (badge: 3)
- Confirmed
- Active (badge: 5)
- Completed
- Cancelled

**Required Implementation:**
- [x] Booking model (seeded in AuthSeeder, but no migrations)
- [ ] Booking migrations with lifecycle states
- [ ] Booking CRUD controllers
- [ ] Booking state machine (draft → pending → confirmed → active → completed/cancelled)
- [ ] Booking confirmation workflow
- [ ] Booking cancellation with refund logic
- [ ] Booking document generation (PDF)
- [ ] Booking status tracking API

**Roadmap Reference:** Phase 3 (Booking Management)

---

### 4. **Offers Module** (Priority: CRITICAL)
**Old Project Structure:**
- All Offers
- Create Offer
- Published
- Drafts (badge: 2)
- Featured
- Tours

**Required Implementation:**
- [ ] Offer model and migrations
- [ ] Offer CRUD controllers
- [ ] Multi-city stay flow
- [ ] Features and tags integration
- [ ] Add-on services linking
- [ ] Offer pricing calculation
- [ ] Offer publishing workflow
- [ ] Offer text editing for sales staff
- [ ] Offer templates

**Roadmap Reference:** Phase 3 (Offer Management)

---

### 5. **Customers Module** (Priority: HIGH)
**Old Project Structure:**
- All Customers
- Add Customer
- VIP Customers
- Reviews
- Messages
- Blocked Customers

**Required Implementation:**
- [ ] Customer model and migrations
- [ ] Customer CRUD controllers
- [ ] Customer type classification (individual, corporate, group)
- [ ] Customer loyalty tier system
- [ ] Customer booking history
- [ ] Customer statistics dashboard
- [ ] Customer reviews system
- [ ] Customer communication history
- [ ] Customer search and filtering

**Roadmap Reference:** Phase 3 (Customer Management)

---

### 6. **Companies Module** (Priority: MEDIUM)
**Old Project Structure:**
- All Companies
- Active Clients
- VIP Partners
- Prospects
- Branding Center
- Analytics

**Required Implementation:**
- [ ] Company model extending tenant concept
- [ ] Company CRUD controllers
- [ ] Relationship status (active, VIP, prospect)
- [ ] B2B company management
- [ ] Branding center (3-color system)
- [ ] Company analytics
- [ ] Company performance tracking

**Roadmap Reference:** Phase 3 (Tenant Management enhancements)

---

### 7. **Financial Module** (Priority: CRITICAL)
**Old Project Structure:**
- Finance Overview
- Invoices
- Payments
- Vouchers
- Financial Analytics

**Required Implementation:**
- [ ] Invoice model and migrations
- [ ] Payment model with status tracking
- [ ] Voucher model with QR code generation
- [ ] Invoice-voucher linkage system
- [ ] Downpayment tracking
- [ ] Payment policies engine
- [ ] Cancellation and refund logic
- [ ] Financial analytics endpoints
- [ ] Revenue collection tracking
- [ ] Overdue payments tracking

**Roadmap Reference:** Phase 3 (Financial Management)

---

### 8. **Resources Module** (Priority: HIGH)
**Old Project Structure:**
- Hotels
- Flights
- Cars
- Tours
- Destinations
- Add-on Services

**Required Implementation:**
- [ ] Hotel model with pricing and availability
- [ ] Flight model with search integration
- [ ] Car model with rental tracking
- [ ] Tour model with scheduling
- [ ] Destination model
- [ ] Add-on services model
- [ ] Resource suggestion engine (by city/date)
- [ ] Resource tagging system
- [ ] Smart resource linking to offers
- [ ] Resource availability calendar

**Roadmap Reference:** Phase 3 (Resource Management)

---

### 9. **Analytics Module** (Priority: MEDIUM)
**Old Project Structure:**
- Executive Analytics
- Real-time Metrics
- Sales Performance
- Customer Insights
- Employee Performance

**Required Implementation:**
- [ ] Analytics service layer
- [ ] Dashboard widget data services
- [ ] Revenue collection analytics
- [ ] Booking conversion rate calculations
- [ ] Financial trends analysis
- [ ] Employee performance metrics
- [ ] Tenant performance reports
- [ ] Real-time metrics broadcasting

**Roadmap Reference:** Phase 4 (Analytics & Reporting)

---

### 10. **Reports Module** (Priority: MEDIUM)
**Old Project Structure:**
- Custom Reports
- Financial Reports
- Booking Reports
- Export functionality

**Required Implementation:**
- [ ] Report generator service
- [ ] Custom report builder
- [ ] Financial reports (P&L, balance sheet)
- [ ] Booking reports (conversion, status distribution)
- [ ] Export service (PDF, Excel, CSV)
- [ ] Scheduled reports
- [ ] Report templates

**Roadmap Reference:** Phase 4 (Analytics & Reporting)

---

### 11. **Templates Module** (Priority: MEDIUM)
**Old Project Structure:**
- Offer Templates
- Voucher Templates
- Invoice Templates
- Email Templates
- Branding Templates

**Required Implementation:**
- [ ] Template model with types
- [ ] Template customization engine
- [ ] Tenant branding in templates (3-color system)
- [ ] Company logo integration
- [ ] Template preview system
- [ ] Template version control
- [ ] Template editor (WYSIWYG)

**Roadmap Reference:** Phase 4 (Template Management)

---

### 12. **Users & RBAC Module** (Priority: CRITICAL)
**Old Project Structure:**
- View All Users
- Roles & Permissions
- Employee Performance
- Activity Logs
- Invite Members

**Required Implementation:**
- [x] User model with tenant relationship
- [x] User seeder with admin credentials
- [ ] User CRUD controllers
- [ ] RBAC system (roles, permissions)
- [ ] Permission model with module grouping (73 permissions across 14 modules)
- [ ] Role-permission assignment
- [ ] User-role assignment system
- [ ] Permission checking middleware
- [ ] Policies for resource authorization
- [ ] Employee performance tracking
- [ ] Activity logging
- [ ] User invitation system

**Roadmap Reference:** Phase 2 (Authentication & Authorization)

---

### 13. **Tenants (B2B Admin) Module** (Priority: HIGH)
**Old Project Structure:**
- Company Registration
- Tenant Management
- Performance Analytics
- Module Customization
- System Analytics

**Required Implementation:**
- [x] Tenant model with UUID primary key
- [x] Tenant branding (3-color system)
- [ ] Tenant registration workflow
- [ ] Tenant onboarding
- [ ] Tenant isolation middleware
- [ ] Tenant context resolver
- [ ] Tenant settings management
- [ ] Tenant analytics tracking
- [ ] Module customization per tenant
- [ ] Multi-tenant performance monitoring

**Roadmap Reference:** Phase 2 (Tenant Management)

---

### 14. **Subscriptions Module** (Priority: MEDIUM)
**Old Project Structure:**
- Overview
- Plans Management
- Billing
- Usage Analytics
- Invoices
- Settings

**Required Implementation:**
- [ ] Subscription model
- [ ] Subscription plans (Basic, Pro, Enterprise)
- [ ] Billing integration (Stripe/Paddle)
- [ ] Usage tracking and metrics
- [ ] Subscription invoices
- [ ] Subscription status (active, cancelled, expired)
- [ ] Plan upgrade/downgrade
- [ ] Subscription settings

**Roadmap Reference:** Not explicitly in roadmap - **NEW MODULE**

---

### 15. **Settings Module** (Priority: HIGH)
**Old Project Structure:**
- Company Information
- Branding Customization
- Currency Management
- QR Code System
- Integrations
- Security Settings

**Required Implementation:**
- [ ] Settings service layer
- [ ] Company information CRUD
- [ ] Branding customization (3-color system)
- [ ] Currency model with exchange rates
- [ ] Currency conversion service
- [ ] QR code generation library
- [ ] QR code service
- [ ] Integration settings (APIs, webhooks)
- [ ] Security settings (2FA, password policies)

**Roadmap Reference:** Phase 4 (Multi-Currency, QR Code System)

---

## 🔧 Database Migrations Needed

Based on the roadmap and old project structure:

1. **Bookings Table**
   - id, tenant_id, offer_id, customer_id, user_id (assigned employee)
   - status (draft, pending, confirmed, active, completed, cancelled)
   - start_date, end_date, total_amount, paid_amount
   - timestamps, soft_deletes

2. **Offers Table**
   - id, tenant_id, title, description, status (draft, published)
   - featured (boolean), pricing JSON
   - timestamps, soft_deletes

3. **Customers Table**
   - id, tenant_id, name, email, phone, type (individual, corporate, group)
   - loyalty_tier, blocked (boolean)
   - timestamps, soft_deletes

4. **Invoices Table**
   - id, tenant_id, booking_id, invoice_number
   - total_amount, paid_amount, status (pending, paid, overdue)
   - timestamps, soft_deletes

5. **Payments Table**
   - id, tenant_id, invoice_id, amount, payment_method
   - status (pending, completed, failed, refunded)
   - timestamps

6. **Vouchers Table**
   - id, tenant_id, booking_id, voucher_number, qr_code
   - status (active, used, expired)
   - timestamps

7. **Resources Tables** (Hotels, Flights, Cars, Tours, Destinations, Addons)
   - Polymorphic relationships
   - Pricing, availability

8. **Notifications Table**
   - id, user_id, type, data (JSON), read_at
   - timestamps

9. **Roles & Permissions Tables**
   - roles: id, tenant_id, name, guard_name
   - permissions: id, name, guard_name, module
   - role_has_permissions, model_has_roles

10. **Templates Table**
    - id, tenant_id, type (offer, invoice, voucher, email)
    - content (JSON/HTML), variables
    - timestamps

11. **Subscriptions Table**
    - id, tenant_id, plan, status, started_at, expires_at
    - timestamps

12. **Activity_logs Table** (Audit Trail)
    - id, user_id, tenant_id, model_type, model_id
    - event, properties (JSON)
    - timestamps

---

## 📊 API Endpoints Needed

All endpoints should be under `/api/v1/` prefix:

### Authentication (✅ DONE)
- POST /api/v1/auth/check-email
- POST /api/v1/auth/validate-credentials
- POST /api/v1/auth/login
- POST /api/v1/auth/logout
- GET /api/v1/auth/me

### Tenants (✅ DONE - Basic)
- GET /api/v1/tenants (requires auth)

### **Bookings** (❌ TODO)
- GET /api/v1/bookings
- POST /api/v1/bookings
- GET /api/v1/bookings/{id}
- PUT /api/v1/bookings/{id}
- DELETE /api/v1/bookings/{id}
- POST /api/v1/bookings/{id}/confirm
- POST /api/v1/bookings/{id}/cancel

### **Offers** (❌ TODO)
- GET /api/v1/offers
- POST /api/v1/offers
- GET /api/v1/offers/{id}
- PUT /api/v1/offers/{id}
- DELETE /api/v1/offers/{id}
- POST /api/v1/offers/{id}/publish

### **Customers** (❌ TODO)
- GET /api/v1/customers
- POST /api/v1/customers
- GET /api/v1/customers/{id}
- PUT /api/v1/customers/{id}
- DELETE /api/v1/customers/{id}

### **Financial** (❌ TODO)
- GET /api/v1/financial/invoices
- POST /api/v1/financial/invoices
- GET /api/v1/financial/payments
- POST /api/v1/financial/payments
- GET /api/v1/financial/vouchers
- POST /api/v1/financial/vouchers

### **Resources** (❌ TODO)
- GET /api/v1/resources/hotels
- GET /api/v1/resources/flights
- GET /api/v1/resources/cars
- GET /api/v1/resources/tours
- GET /api/v1/resources/destinations
- GET /api/v1/resources/addon-services

### **Users & RBAC** (❌ TODO)
- GET /api/v1/users
- POST /api/v1/users
- GET /api/v1/users/rbac/roles
- GET /api/v1/users/rbac/permissions

### **Analytics** (❌ TODO)
- GET /api/v1/analytics/executive
- GET /api/v1/analytics/real-time
- GET /api/v1/analytics/sales

### **Settings** (❌ TODO)
- GET /api/v1/settings/company
- PUT /api/v1/settings/company
- GET /api/v1/settings/branding
- PUT /api/v1/settings/branding

---

## 🎯 Implementation Priority

### Phase 1 (Immediate - Week 1-2)
1. **RBAC System** (Roles & Permissions migrations + seeders)
2. **Bookings Module** (Migrations + Controllers + Tests)
3. **Offers Module** (Migrations + Controllers + Tests)
4. **Customers Module** (Migrations + Controllers + Tests)

### Phase 2 (High Priority - Week 3-4)
1. **Financial Module** (Invoices, Payments, Vouchers)
2. **Resources Module** (Hotels, Flights, Cars, Tours)
3. **Notifications System** (Real-time broadcasting)
4. **Search Module** (Global search across modules)

### Phase 3 (Medium Priority - Week 5-6)
1. **Analytics Module** (Dashboard widgets + Reports)
2. **Templates Module** (Document generation)
3. **Settings Module** (Company, Branding, Currencies)
4. **Companies Module** (B2B enhancements)

### Phase 4 (Lower Priority - Week 7-8)
1. **Subscriptions Module** (Billing integration)
2. **Advanced Analytics** (Real-time metrics)
3. **QR Code System** (Generation + Tracking)
4. **Multi-Currency** (Exchange rates API)

---

## 📝 Notes

1. **Tenant Isolation**: All queries must respect tenant context via middleware
2. **Permission Checks**: Every endpoint requires proper RBAC authorization
3. **API Versioning**: Maintain `/api/v1/` prefix for all endpoints
4. **Testing**: Each module requires feature tests with RefreshDatabase
5. **Documentation**: Update OpenAPI spec for new endpoints
6. **Seeders**: Create comprehensive seeders with sample data for each module

---

## ✅ Checklist for Each Module

- [ ] Database migrations
- [ ] Models with relationships
- [ ] Factories for testing
- [ ] Seeders with sample data
- [ ] Controllers (CRUD)
- [ ] API routes under `/api/v1/`
- [ ] Policies for authorization
- [ ] Feature tests (RefreshDatabase)
- [ ] OpenAPI documentation
- [ ] Service layer (business logic)
- [ ] Frontend pages/components

---

**Last Updated:** December 24, 2025
**Status:** Dashboard navigation structure aligned with old project ✅
**Next Steps:** Implement Phase 1 modules (RBAC, Bookings, Offers, Customers)
