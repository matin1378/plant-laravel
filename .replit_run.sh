#!/usr/bin/env bash
set -e
if ! command -v composer >/dev/null 2>&1; then
  EXPECTED_SIGNATURE="$(wget -q -O - https://composer.github.io/installer.sig)"
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  ACTUAL_SIGNATURE="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"
  if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]; then >&2 echo 'ERROR: Invalid composer installer signature'; rm composer-setup.php; exit 1; fi
  php composer-setup.php --install-dir=/usr/local/bin --filename=composer
  rm composer-setup.php
fi

if [ ! -d vendor ]; then
  composer install --no-interaction --prefer-dist
fi

# Prepare env
if [ ! -f .env ]; then
  cp .env.replit .env || true
fi

mkdir -p database
touch database/database.sqlite

php artisan key:generate || true

php artisan serve --host=0.0.0.0 --port=8000
