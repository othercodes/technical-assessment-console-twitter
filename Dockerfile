# dockyard image for multi-stage build
FROM php:7.2-cli as dockyard

# Install dependencies
RUN apt-get update && apt-get install -y build-essential libzip-dev libxml2-dev zip git

# Install dependency manager
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /opt/unay-santisteban/console-twitter

COPY ./src /opt/unay-santisteban/console-twitter/src
COPY ./tests /opt/unay-santisteban/console-twitter/tests
COPY ./ctwitter /opt/unay-santisteban/console-twitter/ctwitter
COPY ./composer.json /opt/unay-santisteban/console-twitter/composer.json
COPY ./phpunit.xml /opt/unay-santisteban/console-twitter/phpunit.xml

RUN composer install
RUN vendor/bin/phpunit -c phpunit.xml
RUN composer update --no-dev
RUN rm -rf /opt/unay-santisteban/console-twitter/{tests,phpunit.xml}

RUN touch /opt/unay-santisteban/console-twitter/database.sqlite

# main image of the app
FROM php:7.2-cli

# Install dependency manager
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /opt/unay-santisteban/console-twitter

COPY --from=dockyard /opt/unay-santisteban/console-twitter /opt/unay-santisteban/console-twitter

CMD ["php", "./ctwitter"]