---
applyTo: "tests/**/*.php"
---

## Testing Requirements

- Prefer sqlite (file or memory) for feature tests; run migrations + seed minimal data.
- Unit tests: service classes, helpers, model methods (scopes, accessors, mutators).
- Feature tests: endpoints under `/api/v1` with auth, tenant isolation, and permissions.
- Integration tests: booking lifecycle, invoice-voucher linkage, currency conversions.
- Use factories; avoid relying on seeded global state unless explicitly documented.
- Keep tests isolated and deterministic; avoid shared mutable state.
