FROM php:7.4-apache

ARG DATABASE_URL
ENV DATABASE_URL ${DATABASE_URL}

ARG AWS_KEY
ENV AWS_KEY ${AWS_KEY}

ARG AWS_SECRET
ENV AWS_SECRET ${AWS_SECRET}

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

COPY conf/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY conf/apache.conf /etc/apache2/conf-available/z-app.conf
RUN a2enconf z-app
RUN a2enmod rewrite

RUN apt-get update -qq && \
    apt-get install -qy \
    git \
    gnupg \
    unzip \
    zip
RUN docker-php-ext-install -j$(nproc) opcache pdo_mysql
COPY conf/php.ini /usr/local/etc/php/conf.d/app.ini

COPY . /app
WORKDIR /app
RUN composer install