-- Active: 1766587434811@@127.0.0.1@3306@safarstep_beta
This repository hosts SafarStep’s migration to a Laravel 12 backend API and a Tailwind-powered dashboard. Use these instructions to help GitHub Copilot coding agent build, test, and propose changes effectively.

## Development Flow
- Build backend: `composer install` then `php artisan serve`
- Build frontend: `npm ci` then `npm run dev`
- Run queues: `php artisan queue:work`
- Migrate DB: `php artisan migrate`
- Seed data: `php artisan db:seed`
- Run tests: `php artisan test`
- *Old Project located at: /home/safarstep/public_html/v2/.old-project*

## Acceptance Criteria (for PRs)
- Include unit/feature tests for new backend logic
- Update database migrations/seeders when introducing schema changes
- Follow multi-tenant boundaries and tenant scoping in queries/services
- Maintain API versioning under `routes/api.php` with `/api/v1/...`
- Include or update API docs (OpenAPI) when adding/changing endpoints
- Follow PSR-12 and Laravel conventions; run Pint if formatting deviates
- For frontend, follow component guidelines in `.github/instructions/frontend.instructions.md`

## Project Structure (high level)
- `app/` Laravel application code (controllers, models, policies, services)
- `routes/` API and web route definitions
- `database/` migrations, factories, seeders
- `resources/` JS/CSS, Tailwind, views
- `tests/` unit, feature, and integration tests
- `docs/` roadmap, environment, architecture and API docs
- `.github/` Copilot instructions, setup steps, and path-specific rules

## Scope and Task Quality
- Prefer well-scoped tasks with clear acceptance criteria and file targets
- Break large phases into incremental PRs (schema, services, endpoints, UI)
- Avoid broad refactors unless explicitly requested

## Multi-Tenancy and RBAC
- All tenant-bound data access must respect a resolved tenant context
- Use middleware/policies/gates for permission checks
- Place business logic in service classes; keep controllers thin

### Tenant + RBAC Best Practices
- UI/API Headers: Always include `X-Tenant-ID` from resolved context; never hardcode tenant IDs. Frontend should use `window.appConfig.tenantId`.
- Spatie Teams: Set and clear the Spatie `PermissionRegistrar` team ID on every request via middleware; scopes permissions/roles by tenant.
- Controllers: Filter queries by `tenant_id` consistently. Use `withCount()` and relationships with tenant constraints for aggregates.
- Policies: Enforce tenant checks first, then permission checks (e.g., `view_departments`, `edit_departments`).
- Routes: All endpoints under `/api/v1` must validate auth and tenant ownership; forbid cross-tenant resource access.
- Database: Include `tenant_id` columns and indexes on tenant-bound tables; add tenant columns to Spatie tables (`roles`, `model_has_roles`, `model_has_permissions`).
- Testing: In feature tests, set `PermissionRegistrar` team ID and seed minimal tenant data. Verify isolation (cannot access other tenant resources).
- Caching: If caching per tenant, namespace keys by tenant ID and invalidate on tenant-scoped changes.
- Auditing: Log tenant ID in audit logs for traceability.

## Backend Standards
- Framework: Laravel 12, PHP 8.2+
- Auth: Sanctum (token-based); see notes in `docs/ENVIRONMENT.md`
- Queues: Redis (default), fallback to `sync` in local/dev when needed
- Caching/Sessions: Redis where available
- Testing: Prefer sqlite (memory/file) for feature tests; seed as needed

## Frontend Standards
- Tooling: Vite, Tailwind 4; framework selection: Vue 3 or React (TypeScript)
- Component library follows the 3-color tenant branding system
- Accessibility (WCAG AA), i18n, and responsive design are required

## URL Handling - CRITICAL
**ALWAYS respect the APP_URL configuration:**
- Backend: Use Laravel helpers `asset()`, `url()`, `route()` for all URLs
- Frontend: Use `window.appConfig.baseUrl` for page URLs, `window.appConfig.apiUrl` for API calls
- Never hardcode URLs like `/api/v1/...` or `/dashboard/...` - always use the configured base
- Asset paths: `asset('assets/...')` NOT `asset('public/assets/...')` (public is implicit)
- The app may be deployed in subdirectories, so relative paths break without proper base URLs

## SafarStep Brand Identity
- **Brand Name:** SafarStep (multi-tenant SaaS tourism management platform)
- **Primary Color:** `#2A50BC` (Deep Blue) - Used for primary actions, CTAs, and brand elements
- **Secondary Color:** `#10B981` (Emerald Green) - Used for success states, secondary actions
- **Accent Color:** Tenant-specific (customizable per tenant)
- **Color Usage:**
  - Primary buttons and links: `#2A50BC`
  - Success indicators: `#10B981`
  - Gradients: `linear-gradient(135deg, #2A50BC 0%, #1d4ed8 100%)`
  - Focus states: 2px outline with primary color
  - Hover effects: Slightly darker shade of primary
- **Typography:** Bold logo text at 1.5rem with primary color
- **Brand Logos:**
  - Vertical: `public/assets/images/logo/vertical.svg`
  - Horizontal: `public/assets/images/logo/horizontal.svg`
- **Default Tenant Settings:**
  - Demo tenant primary: `#2A50BC`
  - Demo tenant secondary: `#10B981`
  - Settings include prefixes (BK, INV, PAY, VCH, OFF) for reference numbers

## Business Terminology (UI Copy)
- Avoid technical terms like "tenant" in user-facing UI.
- Prefer business-friendly terms: "Organization", "Company", or "Account" depending on context.
- Examples:
  - "Tenant scoped" → "Organization-wide"
  - "Select tenant" → "Select organization"
- Keep technical terms (tenant IDs, headers) within backend code, APIs, and developer docs only.
- Ensure all labels, helper text, and banners reflect business language consistently across views.

## Using Copilot Coding Agent
- Respect repository-wide and path-specific instructions under `.github/instructions/`
- Use `.github/copilot-setup-steps.yml` to preinstall dependencies when running remotely
- When creating PRs, include a concise summary, changed modules, and test coverage
- Use small, reviewable commits and keep changes tightly scoped to the task

## Path-Specific Guidance
See:
- `.github/instructions/php.instructions.md` (Laravel backend)
- `.github/instructions/frontend.instructions.md` (Tailwind + Vite + SPA)
- `.github/instructions/tests.instructions.md` (tests)

## Roadmap and Modules
Implementation should align with `docs/ROADMAP.md` (Projects 1 and 2). Phases define priorities and endpoints.

## Model Context Protocol (MCP) and Custom Agents
- MCP servers may be leveraged for test or browser automation
- See `AGENTS.md` for custom agent profiles (backend, frontend, docs, QA)

## Formatting & Linting
- PHP: `vendor/bin/pint`
- JS/TS: follow project defaults; keep Vite config minimal and fast

## Security and Data
- Avoid committing secrets
- Validate and authorize every endpoint
- Sanitize external input; enforce rate-limiting and CORS rules per `config/`

## CI/CD Notes
- Prefer deterministic installs (`composer install --no-dev --prefer-dist` in CI)
- Use `php artisan config:cache`/`route:cache` in production builds
