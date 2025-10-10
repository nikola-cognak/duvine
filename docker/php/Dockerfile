FROM php:7.4-fpm
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql