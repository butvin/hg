FROM node:22.14.0-alpine AS nodejs
FROM php:8.4-fpm-alpine

ARG APP_ENV=${APP_ENV}

COPY docker/php /common/

RUN apk add --update linux-headers bash

RUN apk add --no-cache --virtual build-dependencies icu-dev libpq-dev libzip-dev zip libldap openldap-dev libtool intltool $PHPIZE_DEPS \
    libjpeg-turbo libpng freetype libwebp libxpm libjpeg-turbo-dev libpng-dev freetype-dev libwebp-dev libxpm-dev \
    && apk add --update rabbitmq-c rabbitmq-c-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg  --with-webp --with-xpm \
    && docker-php-ext-install -j$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) mysqli opcache pdo pdo_pgsql pgsql pdo_mysql zip fileinfo exif ldap intl bcmath gd \
    && pecl update-channels && pecl install amqp redis \
    && docker-php-ext-enable amqp redis


# Development extensions
RUN if [ ${APP_ENV} = "dev" ]; then \
        apk add --no-cache --update bash bash-completion tree htop vim fish ; \
        pecl update-channels && pecl install xdebug && docker-php-ext-enable xdebug ; \
    fi


# fix ldap config
RUN echo "TLS_REQCERT never" >> /etc/openldap/ldap.conf

# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# nodejs
COPY --from=nodejs /usr/local/bin/node /usr/local/bin/
COPY --from=nodejs /usr/local/include/node /usr/local/include/node
COPY --from=nodejs /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=nodejs /usr/local/share/doc/node /usr/local/share/doc/node

RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm
RUN npm install -g npm@latest


## CONFIGURE
ARG USER_NAME=www-data
COPY docker/php/custom.ini /usr/local/etc/php/custom.ini
COPY docker/php/www.conf docker/php/xdebug.ini /usr/local/etc/php/conf.d/

# supervisor
RUN apk add --update --no-cache supervisor
COPY docker/php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# cron: https://github.com/eltorio/dcron
RUN apk add --update --no-cache dcron \
    && touch /var/log/cron.log \
    && chmod 0777 /var/log/cron.log

COPY --chown=${USER_NAME}:${USER_NAME} docker/php/tasks.cron /var/spool/cron/crontabs/${USER_NAME}

RUN chmod 600 /var/spool/cron/crontabs/${USER_NAME} \
    && chmod u=rwx,g=wx,o=t /var/spool/cron/crontabs \
    && addgroup ${USER_NAME} wheel \
    && chown ${USER_NAME}:wheel /var/spool/cron/crontabs \
    && chown root:${USER_NAME} /var/spool/cron/crontabs/${USER_NAME}

# cleanup
RUN apk del libjpeg-turbo-dev libpng-dev freetype-dev libwebp-dev libxpm-dev $PHPIZE_DEPS \
    && rm -rf /var/cache/apk/* /tmp/*

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]