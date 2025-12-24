This repository hosts SafarStep’s migration to a Laravel 12 backend API and a Tailwind-powered dashboard. Use these instructions to help GitHub Copilot coding agent build, test, and propose changes effectively.

## Development Flow
- Build backend: `composer install` then `php artisan serve`
- Build frontend: `npm ci` then `npm run dev`
- Run queues: `php artisan queue:work`
- Migrate DB: `php artisan migrate`
- Seed data: `php artisan db:seed`
- Run tests: `php artisan test`

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
