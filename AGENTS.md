# Custom Agents

Define focused Copilot agents for recurring workflows. Agents inherit repo instructions and MCP tools unless restricted.

## Backend Agent (Laravel)
- Scope: `app/`, `routes/`, `database/`, `config/`, `tests/`
- Focus: services, migrations, policies, middleware, API endpoints (`/api/v1`)
- Guardrails: respect tenant isolation; enforce RBAC; add tests for new logic
- Tools: read, search, edit files; run tests; recommend migrations/seeders

## Frontend Agent (Tailwind + Vite)
- Scope: `resources/`, `vite.config.js`
- Focus: component library, pages, routing, API client, accessibility, i18n, RTL
- Guardrails: use TypeScript; follow 3-color branding system; responsive first
- Tools: read, search, edit files; propose visual/UX improvements

## Docs Agent
- Scope: `docs/`, `README.md`, OpenAPI
- Focus: environment setup, API docs, architecture, deployment
- Guardrails: keep docs concise and accurate; cross-link related modules

## QA/Test Agent
- Scope: `tests/`
- Focus: coverage for services and endpoints, sqlite testing, fixtures
- Guardrails: deterministic tests, clear Given/When/Then, minimal coupling
