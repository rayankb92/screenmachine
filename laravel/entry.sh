#!/bin/bash

echo "Laravel folder ENTRYPOINT: Starting PHP-FPM je suis ici: $PWD"

set -e


chmod -R 777 /var/www/html/storage/*
chmod -R 777 /var/www/html/storage/*/*  # solution temporaire
chmod -R 777 /var/www/html/bootstrap/cache
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf

# composer install --no-interaction --no-plugins --no-scripts

# npm install

# php artisan migrate --force

# php artisan queue:work&

# php artisan reverb:start&

# echo "chmoding storage"


# npm run dev&

# echo "sleeping 10s"

# sleep 10

# echo "end sleeping"

# echo "Starting PHP-FPM"
# php-fpm -F