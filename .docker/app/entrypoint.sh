#!/bin/bash

composer install
php artisan key:generate
php artisan migrate
chown -R www-data:root /var/www
chmod -R 777 /var/www
php-fpm