FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    git\
    zip\
    libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

RUN a2enmod rewrite

RUN docker-php-ext-install pdo pdo_pgsql

RUN echo "Listen 80" > /etc/apache2/ports.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html/

EXPOSE 80