#!/bin/bash
# cPanel Git post-deploy hook for BD Growth Suite Laravel.
# Called from .cpanel.yml after cPanel pulls the tracked branch.
set -euo pipefail

cd "${DEPLOYPATH:-$(pwd)}"

PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"

if ! command -v "$COMPOSER_BIN" >/dev/null 2>&1; then
  COMPOSER_BIN="/usr/local/bin/composer"
fi

if [ ! -f .env ]; then
  echo "ERROR: .env is missing in ${DEPLOYPATH}. Copy .env.example and configure secrets before deploying."
  exit 1
fi

$COMPOSER_BIN install --no-dev --prefer-dist --optimize-autoloader --no-interaction

if [ -f package-lock.json ] && command -v npm >/dev/null 2>&1; then
  npm ci --omit=dev
  npm run build
fi

$PHP_BIN artisan migrate --force
$PHP_BIN artisan storage:link --force 2>/dev/null || true
$PHP_BIN artisan bdgs:optimize-production

echo "Deploy finished for $(basename "$PWD")."
