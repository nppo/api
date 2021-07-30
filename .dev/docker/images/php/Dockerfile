ARG PHP_VERSION

FROM php:${PHP_VERSION}-fpm AS local-php

ARG APP_ENV=live
ARG ADDITIONAL_SOFTWARE=

WORKDIR /var/www/html

RUN apt update -y
RUN bash -c "if [[ ! -z '$ADDITIONAL_SOFTWARE' ]] ; then apt install -y $ADDITIONAL_SOFTWARE ; fi "

# Prepare installation of extensions
COPY ./.dev/docker/images/php/resources/extension-installer.sh /opt/php-ext-install.sh

# Configure GD with JPG support
RUN apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Install basic PHP extensions
RUN /opt/php-ext-install.sh bcmath ctype fileinfo json openssl tokenizer xml zip curl exif

RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

# Install PHP Database extensions
RUN /opt/php-ext-install.sh mysql mysqli pdo_mysql pdo

# Should always be inside the project
COPY ./.dev/docker/images/php/resources/custom.ini /usr/local/lib/php.conf.d/custom.ini
COPY ./.dev/docker/images/php/resources/extensions.ini /usr/local/lib/php.conf.d/extensions.ini

# For PHP-FPM; Make conditional
# Can be moved to a general repository
RUN rm -f /usr/local/etc/fpm.d/default.conf
COPY ./.dev/docker/images/php/resources/php-fpm.conf /usr/local/etc/php-fpm.conf

# Setup application
COPY . /var/www/html
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN bash -c "if [[ '$APP_ENV' = 'live' ]] ; then composer --no-interaction --no-cache install --optimize-autoloader --no-dev --no-scripts ; fi"

RUN rm /opt/php-ext-install.sh
