#!/bin/sh

set -e

echo "Starting YaayDoom backend container"

# Remove any cached provider manifests copied from the repository.
# They can reference dev-only packages and break a production image built with --no-dev.
rm -f bootstrap/cache/*.php

if [ -z "$APP_KEY" ]; then
  echo "APP_KEY is not set. Provide it through the container environment before starting."
  exit 1
fi

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  echo "Running migrations"
  php artisan migrate --force
fi

exec "$@"
