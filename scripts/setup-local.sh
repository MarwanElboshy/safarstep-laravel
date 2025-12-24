#!/usr/bin/env bash
set -euo pipefail

echo "[setup] Checking prerequisites..."
command -v php >/dev/null || { echo "PHP is required"; exit 1; }
command -v composer >/dev/null || { echo "Composer is required"; exit 1; }
command -v node >/dev/null || { echo "Node.js is required"; exit 1; }
command -v npm >/dev/null || { echo "npm is required"; exit 1; }

if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate --force
fi

echo "[setup] Installing PHP dependencies..."
composer install --prefer-dist --no-interaction

echo "[setup] Installing JS dependencies..."
npm ci || npm install

if [ ! -f database/database.sqlite ]; then
  mkdir -p database
  touch database/database.sqlite
fi

echo "[setup] Running migrations..."
php artisan migrate --force || true

chmod -R ug+rw storage bootstrap/cache || true

echo "[setup] Done. Try running:\n  php artisan serve\n  php artisan queue:work\n  npm run dev"
