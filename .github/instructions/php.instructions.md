---
applyTo:
  - "app/**/*.php"
  - "routes/**/*.php"
  - "database/**/*.php"
  - "config/**/*.php"
---

## Laravel Backend Requirements

- Use Laravel 12 conventions and PSR-12 style.
- Keep controllers thin; place business logic in service classes under `app/Services`.
- Implement authorization via policies and gates; middleware for tenant + auth.
- Respect tenant context in all queries; never leak cross-tenant data.
- Prefer Eloquent relationships, scopes, and Resources (transformers) for API responses.
- All new endpoints belong under `/api/v1` with explicit validation and rate limiting.

## Data and Migrations
- Create explicit migrations per module; avoid destructive changes without backups.
- Add indexes for performance based on query patterns; include foreign keys.
- Provide seeders for default roles, permissions, tenants, and currencies.

## Services and Modules
- Implement services per `docs/ROADMAP.md` (e.g., `TenantService`, `AuthService`, `RBACService`, etc.).
- Inject services via constructors; do not use facades in service internals.
- Write unit tests for services; feature tests for endpoints.

## Validation and Errors
- Use Form Request classes or `Validator::make` with precise rules.
- Return standardized JSON errors with HTTP status codes and error codes.

## Performance
- Use `with()` for eager loading; avoid N+1.
- Cache read-heavy endpoints where appropriate; invalidate on writes.

## Security
- Enforce CORS, CSRF (where applicable), and auth guards correctly.
- Sanitize inputs; never trust client-sent IDs without ownership checks.
