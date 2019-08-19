FROM phpdockerio/php73-fpm:latest
WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install  php-memcached php7.3-mysql php7.3-pgsql php-redis php-xdebug php7.3-bcmath php-imagick php7.3-ldap php-yaml \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

ADD . /application
#ADD ./docker/php-fpm/php-ini-overrides.ini /etc/php/7.3/fpm/conf.d/99-overrides.ini
#ADD ./docker/php-fpm/www.conf /etc/php/7.3/fpm/pool.d/www.conf
#ADD ./docker/php-fpm/pool-overrrides.conf /etc/php/7.3/fpm/pool.d/99-overrides.ini
ADD ./docker/php-fpm/xdebug.ini /etc/php/7.3/fpm/conf.d/20-xdebug.ini

RUN chmod -R 777 log
RUN chmod -R 777 var/cache

#EXPOSE 9001

ENV XDEBUG_REMOTE_HOST_IP=docker.for.mac.localhost
ENV XDEBUG_REMOTE_PORT=9001
ENV PHP_IDE_CONFIG="serverName=k8s-demo"



