#!/bin/sh

set -e

echo "Starting YaayDoom backend container"

# Remove cached package manifests that may reference dev-only providers.
rm -f bootstrap/cache/*.php

mkdir -p \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/testing \
  storage/framework/views \
  storage/logs \
  bootstrap/cache

if [ -f storage/oauth-private.key ]; then
  chmod 600 storage/oauth-private.key
fi

if [ -f storage/oauth-public.key ]; then
  chmod 660 storage/oauth-public.key
fi

if [ -z "$APP_KEY" ]; then
  echo "APP_KEY is not set. Provide it through the container environment before starting."
  exit 1
fi

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  echo "Running migrations"
  php artisan migrate --force
fi

if [ "${USE_MOCK_DATA:-false}" = "true" ]; then
  echo "Running migrations and seeding mock data"
  php artisan migrate --force --seed
fi

exec "$@"
