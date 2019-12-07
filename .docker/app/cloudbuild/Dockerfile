FROM php:7.3.6-fpm-alpine3.9

#dependencias bash
RUN apk add bash

#dependencias mysql-client
RUN apk add mysql-client

#extensao php install
RUN docker-php-ext-install pdo pdo_mysql


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer



WORKDIR /var/www/backend
RUN rm -rf /var/www/html
#COPY . /var/www
RUN ln -s public html
#RUN chown -R www-data:root /var/www
#RUN chmod -R 777 /var/www
#laravel config
#RUN composer install
#RUN cp .env.example .env
#RUN php artisan key:generate
#RUN php artisan config:cache


EXPOSE 9000

ENTRYPOINT ["php-fpm"]
