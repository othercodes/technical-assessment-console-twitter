# dockyard image for multi-stage build
FROM php:7.2-cli as dockyard

# Install dependencies
RUN apt-get update && apt-get install -y build-essential libzip-dev libxml2-dev zip git sqlite3

# Install dependency manager
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /opt/unay-santisteban/console-twitter

COPY ./src /opt/unay-santisteban/console-twitter/src
COPY ./database /opt/unay-santisteban/console-twitter/database
COPY ./applications /opt/unay-santisteban/console-twitter/applications
COPY ./composer.json /opt/unay-santisteban/console-twitter/composer.json

COPY ./tests /opt/unay-santisteban/console-twitter/tests
COPY ./phpunit.xml /opt/unay-santisteban/console-twitter/phpunit.xml

RUN composer install
RUN vendor/bin/phpunit -c phpunit.xml
RUN composer install --no-dev
RUN rm -rf /opt/unay-santisteban/console-twitter/{tests,phpunit.xml}

RUN touch database.sqlite && sqlite3 database.sqlite < database/install.sql

# main image of the app
FROM php:7.2-cli

# Install dependency manager
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /opt/unay-santisteban/console-twitter/applications/console/bin

COPY --from=dockyard /opt/unay-santisteban/console-twitter /opt/unay-santisteban/console-twitter

CMD ["php", "./console"]