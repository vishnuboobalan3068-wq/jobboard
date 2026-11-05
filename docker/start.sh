#!/bin/sh
# Starts the live demo. The free server has no permanent disk, so the database
# is rebuilt with demo data on every start.
set -e

export APP_KEY="${APP_KEY:-base64:$(php -r 'echo base64_encode(random_bytes(32));')}"
export APP_URL="${RENDER_EXTERNAL_URL:-${APP_URL:-http://localhost}}"

touch database/database.sqlite
php artisan migrate:fresh --seed --force

# Laravel's router file expects to run from inside public/
cd public
exec php -S "0.0.0.0:${PORT:-10000}" ../vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php
