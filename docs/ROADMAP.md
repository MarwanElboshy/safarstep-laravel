# SafarStep Migration Plan: Laravel + Tailwind Projects

This roadmap defines the phases and work items for migrating SafarStep to a Laravel 12 backend API and a Tailwind-powered dashboard. Treat each phase as an incremental PR milestone.

## PROJECT 1: Laravel Backend API

### Phase 1: Foundation & Infrastructure
1. Project Setup & Configuration
   - Initialize Laravel project with Sanctum authentication (using token-based auth)
   - Configure multi-tenant database architecture
   - Set up environment variables and configuration files
   - Configure CORS and API rate limiting
   - Set up Redis for caching and session management
   - Configure queue system for background jobs

2. Database Schema & Migrations
   - Tenants table with branding fields (primary_color, secondary_color, accent_color)
   - Users table with department and role relationships
   - Roles and permissions tables (RBAC system)
   - Departments table with hierarchical structure
   - Bookings table with lifecycle states
   - Tour offers table with multi-city support
   - Invoices and payments tables
   - Vouchers table with QR code support
   - Resource management tables (hotels, flights, cars, tours)
   - Destinations, features, and tags tables
   - Add-on services table
   - Currencies table with exchange rates
   - Audit logs table for activity tracking
   - Notifications table
   - Templates table (offers, invoices, vouchers)
   - Indexes for performance optimization

3. Seeders & Default Data
   - Seed 73 permissions across 14 modules
   - Seed default roles (Super Admin, Admin, Manager, Employee)
   - Seed default departments for travel agencies
   - Seed sample tenants with branding
   - Seed currency exchange rates
   - Seed default notification templates

### Phase 2: Authentication & Authorization
1. Authentication System
   - Implement token auth with Laravel Sanctum
   - Login/register endpoints
   - Password reset functionality
   - Token refresh mechanism
   - Logout and token blacklist
   - Multi-tenant authentication middleware
   - Remember me functionality

2. RBAC System
   - Role model with permissions relationship
   - Permission model with module grouping
   - Role-permission assignment
   - User-role assignment system
   - Permission checking middleware
   - Policies for resource authorization
   - Permission-based API gates

3. Tenant Management
   - Tenant isolation middleware
   - Tenant context resolver
   - Tenant branding service (3-color system)
   - Tenant onboarding workflow
   - Tenant settings management
   - Tenant analytics tracking

### Phase 3: Core Business Modules
1. User Management
   - User model with department relationship
   - User CRUD endpoints
   - User search and filtering
   - User activity logging
   - Employee performance tracking
   - User profile management
   - User status management (active/inactive)

2. Department Management
   - Department model with hierarchy support
   - Department CRUD endpoints
   - Department member assignment
   - Department statistics
   - Department hierarchy retrieval
   - Default department creation during tenant setup

3. Booking Management
   - Booking model with offer relationship
   - Booking lifecycle state machine (draft → confirmed → active → completed)
   - Booking CRUD endpoints
   - Booking confirmation workflow
   - Booking cancellation with refund logic
   - Booking document generation
   - Booking status tracking

4. Offer Management
   - Offer model with resources relationship
   - Offer CRUD endpoints
   - Multi-city stay flow
   - Features and tags integration
   - Add-on services linking
   - Offer pricing calculation
   - Offer publishing workflow
   - Offer text editing for sales staff

5. Financial Management
   - Invoice model with payment tracking
   - Payment model with status tracking
   - Voucher model with QR code generation
   - Invoice-voucher linkage system
   - Downpayment tracking
   - Payment policies engine
   - Cancellation and refund logic
   - Financial analytics

6. Resource Management
   - Hotel model with pricing and availability
   - Flight model with search integration
   - Car model with rental tracking
   - Tour model with scheduling
   - Resource suggestion engine (by city/date)
   - Resource tagging system
   - Smart resource linking to offers
   - Resource availability calendar

7. Customer Management
   - Customer model with loyalty tier
   - Customer CRUD endpoints
   - Customer type classification (individual, corporate, group)
   - Customer booking history
   - Customer statistics dashboard
   - Customer search and filtering

### Phase 4: Advanced Features
1. Multi-Currency System
   - Currency model with exchange rates
   - Currency conversion service
   - Real-time exchange rate API integration
   - Currency settings per tenant
   - Multi-currency calculation logic for offers/invoices/vouchers
   - Currency display formatting

2. QR Code System
   - QR code generation library
   - QR code generation service
   - Unique QR codes for offers and vouchers
   - QR code tracking system
   - QR code status display endpoints
   - QR code scanning validation

3. Notification System
   - Notification model with types
   - Notification service (email, SMS, in-app)
   - Payment alerts/reminders
   - Client arrival notifications
   - Offer modification alerts
   - Booking revision notifications
   - Real-time notification broadcasting

4. Template Management
   - Template model for offers, invoices, vouchers
   - Template customization engine
   - Tenant branding in templates (3-color system)
   - Company logo integration
   - Template preview system
   - Template version control

5. Analytics & Reporting
   - Dashboard widget data services
   - Revenue collection analytics
   - Overdue payments tracking
   - Booking conversion rate calculations
   - Financial trends analysis
   - Employee performance metrics
   - Tenant performance reports

6. Audit Trail & Logging
   - Comprehensive audit logging system
   - Activity tracking for all operations
   - Audit log query endpoints
   - Audit log export functionality
   - Security event logging

### Phase 5: API Endpoints Structure
- Authentication, Tenants, Users, Departments, Bookings, Offers, Financials, Resources, Customers, Analytics endpoints as enumerated in the plan. All under `/api/v1/...`.

### Phase 6: Services & Business Logic
- TenantService, AuthService, RBACService, BookingService, OfferService, FinancialService, ResourceService, CurrencyService, NotificationService, TemplateService, AnalyticsService, AuditService, QRCodeService

### Phase 7: Testing & Quality Assurance
- Unit, Feature, Integration coverage across modules; rate limiting and isolation checks

### Phase 8: Documentation
- OpenAPI/Swagger; database schema; service layer; permissions; deployment; environment

## PROJECT 2: Tailwind Frontend Dashboard

### Phase 1: Project Setup & Configuration
- Vite + Vue 3 / React (TypeScript), Tailwind 4, Alpine.js (optional), Axios, Router, State

### Phase 2: Authentication & Layout
- Auth pages; multi-tenant selector; protected routes; dashboard shell (sidebar, header, breadcrumbs)

### Phase 3: Reusable Component Library (7 Core Components)
- Page Header, Dashboard Stats, Search & Filters, Bulk Actions Toast, Data Table, Modal, Form Field

### Phase 4: Dashboard & Analytics
- Main dashboard widgets + analytics dashboard with charts, filters, export

### Phase 5: Core Module Interfaces
- Users, Departments, Bookings, Offers, Financials, Resources, Customers

### Phase 6: Advanced Features
- Tenant Branding Settings, Notifications, Template Management, QR Code, Multi-Currency UI

### Phase 7: Search, Filtering & Pagination
- Global search; advanced filters; pagination & infinite scroll

### Phase 8: Responsive Design & Mobile
- Mobile- and tablet-optimized layouts

### Phase 9: Performance & UX
- Loading states, error handling, animations and micro-interactions

### Phase 10: Accessibility & Internationalization
- WCAG AA; i18n; RTL; localization

### Phase 11: Testing & Quality
- Component tests; E2E; performance

### Phase 12: Deployment & Documentation
- Production build; Storybook; user/admin guides

## Integration & Testing Plan
- Backend-Frontend integration (Postman/Insomnia, consistency, auth, permissions, realtime)
- Cross-browser testing; mobile browsers; responsive validation
- Security testing (XSS, CSRF, SQLi, auth bypass, privilege escalation)

## Priority Matrix
- Critical: Laravel setup + schema; Auth; RBAC; Multi-tenant; Frontend setup + components; Auth pages + layout; Core dashboard
- High: Users, Departments, Bookings, Offers, Financials
- Medium: Resources, Customers, Multi-currency, QR, Notifications
- Lower: Templates, Analytics, advanced optimizations
