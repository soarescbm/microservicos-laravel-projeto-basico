FROM php:7.3.6-fpm-alpine3.9

#dependencias bash
RUN apk add bash

#dependencias mysql-client
RUN apk add mysql-client

#extensao php install
RUN docker-php-ext-install pdo pdo_mysql

#dockerize
RUN apk add --no-cache openssl

#ENV DOCKERIZE_VERSION v0.6.1
#RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
#    && tar -C /usr/local/bin -xzvf dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
#    && rm dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz


#composer install
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"


WORKDIR /var/www
RUN rm -rf /var/www/html
#COPY . /var/www
RUN ln -s public html
RUN chown -R www-data:root /var/www
RUN chmod -R 777 /var/www
#laravel config
#RUN composer install
#RUN cp .env.example .env
#RUN php artisan key:generate
#RUN php artisan config:cache


EXPOSE 9000

ENTRYPOINT ["php-fpm"]