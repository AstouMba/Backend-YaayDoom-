#!/bin/sh

set -e

echo "Starting YaayDoom backend container"

if [ -z "$APP_KEY" ]; then
  echo "APP_KEY not set, generating one"
  php artisan key:generate --force --ansi || true
fi

php artisan config:clear || true
php artisan route:clear || true
php artisan cache:clear || true

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  echo "Running migrations"
  php artisan migrate --force || true
fi

exec "$@"
