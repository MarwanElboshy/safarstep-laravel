# Environment and Architecture Notes

This project targets Laravel 12 (PHP 8.2+) with Sanctum for token-based API auth, Redis for queues/cache/sessions, and Vite + Tailwind 4 for the dashboard.

## Services
- App: PHP-FPM 8.3 with Composer
- Web: Nginx serving `public/`
- DB: MySQL 8 (or MariaDB 10.6+)
- Cache/Queue/Session: Redis 7

## Auth
- Laravel Sanctum for SPA/Token auth. For JWT-style flows, use long-lived Personal Access Tokens or an optional JWT package (deferred).

## Multi-Tenancy
- Tenant context must be resolved per request (subdomain, header, or token claim). We recommend a dedicated middleware and a `TenantResolver` service. Package selection (e.g., `stancl/tenancy`) is deferred to Phase 1.

## Queues and Jobs
- Default to `redis` driver; `sync` allowed locally. Ensure failed jobs table exists.

## CORS & Rate-limiting
- Configure `config/cors.php` and `app/Http/Kernel.php` throttling for `/api/v1/*` routes.

## Testing
- Prefer sqlite (file) for feature tests. Use memory sqlite for unit tests. Seed minimal fixtures.

## Local Setup (Linux)
1. Ensure PHP 8.3, Composer, Node 20, npm are installed.
2. Copy env and generate app key:
   ```bash
   cp -n .env.example .env
   php artisan key:generate
   ```
3. Install deps and migrate:
   ```bash
   composer install
   npm ci
   php artisan migrate
   ```
4. Run services:
   ```bash
   php artisan serve
   php artisan queue:work
   npm run dev
   ```

## Docker (Optional)
Use `docker-compose.yml` to run app+nginx+mysql+redis locally. Export ports 8000 (web), 3306 (DB), 6379 (Redis).
