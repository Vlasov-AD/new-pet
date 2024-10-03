FROM php:8.3-apache

WORKDIR /var/www

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql pgsql

RUN apt-get -y update \
    && apt-get install -y libicu-dev\
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

RUN  apt-get update \
  && apt-get install -y wget

RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

RUN a2enmod headers
RUN a2enmod rewrite
RUN service apache2 restart

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer