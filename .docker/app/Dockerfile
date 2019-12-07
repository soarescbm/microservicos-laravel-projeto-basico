FROM php:7.3.6-fpm-alpine3.10

#dependencias bash
RUN apk add bash

#dependencias mysql-client
RUN apk add mysql-client freetype-dev libjpeg-turbo-dev libpng-dev nodejs npm

#extensao php install
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include --with-png-dir=/usr/include
RUN docker-php-ext-install -j$(nproc) gd

#dockerize
RUN apk add --no-cache openssl

ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer



WORKDIR /var/www/
RUN rm -rf /var/www/html
#COPY . /var/www
RUN ln -s backend/public html
#RUN chown -R www-data:root /var/www
#RUN chmod -R 777 /var/www
#laravel config
#RUN composer install
#RUN cp .env.example .env
#RUN php artisan key:generate
#RUN php artisan config:cache


EXPOSE 9000

ENTRYPOINT ["php-fpm"]
