#!/bin/bash

# Deploy - to be executed as normal user

cd /var/www/layer7-console

php artisan down

git pull
composer install --no-interaction --optimize-autoloader --no-dev
sudo chown -R www-data:www-data
npm install
npm run build

php artisan storage:link
php artisan optimize:clear

php artisan down

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:upgrade

php artisan up

sudo chown -R www-data:www-data
