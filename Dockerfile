FROM php:8.1 as php

ARG NODE_VERSION=20

RUN apt-get update -y
RUN apt-get install -y unzip libpq-dev libcurl4-gnutls-dev curl
RUN docker-php-ext-install pdo pdo_mysql bcmath
RUN pecl install -o -f redis && rm -rf  /tmp/pear && docker-php-ext-enable redis
RUN apt-get update -y
RUN curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

WORKDIR /var/www/html
COPY . .
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer
COPY ./entrypoint /usr/local/bin/start-container

EXPOSE 80

ENTRYPOINT ["start-container"]


