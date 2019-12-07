#!/bin/bash

#FRONTEND
npm config set cache /var/www/.npm-cache --global
cd /var/www/frontand && npm install && cd ..

#BACKEND
cd backend
composer install
php artisan key:generate
php artisan migrate
chown -R www-data:root /var/www
chmod -R 777 /var/www
php-fpm