#!/usr/bin/env bash
set -euo pipefail

# deploy.sh - simple Laravel deploy script
# Usage: run on the server as the deploy user from the project root
#       or pass the project path as first argument: ./deploy.sh /var/www/ashastables

PROJECT_DIR="${1:-/var/www/ashastables}"
BRANCH="${2:-main}"
RUN_MIGRATIONS=${3:-false} # set to 'true' to run migrations

echo "Deploying ${PROJECT_DIR} (branch ${BRANCH})"

if [ ! -d "$PROJECT_DIR" ]; then
  echo "Error: project directory $PROJECT_DIR does not exist" >&2
  exit 1
fi

cd "$PROJECT_DIR"

# ensure correct user permissions if necessary (uncomment to use)
# sudo chown -R $USER:www-data .

# stash local changes
if [ -n "$(git status --porcelain)" ]; then
  echo "Local changes detected: stashing"
  git stash push -m "deploy-$(date +%s)" || true
fi

# fetch and update
git fetch origin
git checkout "$BRANCH"
# try rebase first to keep history clean
if git pull --rebase origin "$BRANCH"; then
  echo "Pulled (rebase)"
else
  echo "Rebase failed, falling back to merge"
  git pull origin "$BRANCH"
fi

# composer install
if command -v composer >/dev/null 2>&1; then
  composer install --no-dev --prefer-dist --optimize-autoloader
else
  echo "composer not found, please install composer or run manually" >&2
fi

# (optional) run migrations
if [ "${RUN_MIGRATIONS}" = "true" ]; then
  php artisan migrate --force
fi

# cache/optimize
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true
php artisan optimize || true

# restart services (adjust for your environment)
if command -v systemctl >/dev/null 2>&1; then
  # try common php-fpm names
  if systemctl list-units --type=service | grep -q "php"; then
    echo "Restarting php-fpm (if present)"
    sudo systemctl restart php8.1-fpm 2>/dev/null || sudo systemctl restart php7.4-fpm 2>/dev/null || sudo systemctl restart php-fpm 2>/dev/null || true
  fi
  if systemctl list-units --type=service | grep -q nginx; then
    echo "Restarting nginx"
    sudo systemctl restart nginx || true
  fi
fi

# show last commits
echo "Deployed. Latest commits:"
git --no-pager log --oneline -n 5

echo "Done"
