FROM node:22.14.0-alpine AS nodejs
FROM php:8.4-fpm-alpine AS base

ARG APP_ENV=${APP_ENV}
ARG TZ=${TZ}
ENV TZ=${TZ}

COPY docker/php /common/docker/php/

RUN apk add --no-cache --update linux-headers curl bash zip tzdata supervisor dcron libldap libtool intltool libjpeg-turbo libpng freetype libwebp libxpm rabbitmq-c

RUN apk add --no-cache --virtual build-dependencies icu-dev libpq-dev libzip-dev openldap-dev libjpeg-turbo-dev libpng-dev freetype-dev libwebp-dev libxpm-dev rabbitmq-c-dev $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg  --with-webp --with-xpm \
    && docker-php-ext-install -j$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) mysqli opcache pdo pdo_pgsql pgsql pdo_mysql zip fileinfo exif ldap intl bcmath gd \
    && pecl update-channels && pecl install amqp redis \
    && docker-php-ext-enable amqp redis

# composer
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

# dev tools
RUN if [ ${APP_ENV} = "dev" ]; then \
        apk add --no-cache --update bash-completion tree htop vim acl; \
        pecl install xdebug && docker-php-ext-enable xdebug; \
    fi

# nodejs
COPY --from=nodejs /usr/local /usr/local
RUN npm install -g npm@latest && npm cache clean --force




# configure
ARG USER_NAME=www-data

RUN cp /usr/share/zoneinfo/$TZ /etc/localtime && echo "$TZ" > /etc/timezone && echo "date.timezone=$TZ" > ${PHP_INI_DIR}/conf.d/timezone.ini && \
    echo "TLS_REQCERT never" >> /etc/openldap/ldap.conf && \
    cp /common/docker/php/custom.ini ${PHP_INI_DIR}/ && \
    cp /common/docker/php/xdebug.ini ${PHP_INI_DIR}/conf.d/ && \
    cp /common/docker/php/www.conf ${PHP_INI_DIR}/conf.d/ && \
    mkdir -p /etc/supervisor/conf.d && cp /common/docker/php/supervisord.conf /etc/supervisor/conf.d/ && \
    cp /common/docker/php/tasks.cron /var/spool/cron/crontabs/${USER_NAME} && chown ${USER_NAME}:${USER_NAME} /var/spool/cron/crontabs/${USER_NAME} && chmod 600 /var/spool/cron/crontabs/${USER_NAME} && \
    touch /var/log/cron.log && chmod 0777 /var/log/cron.log && \
    chmod u=rwx,g=wx,o=t /var/spool/cron/crontabs && addgroup ${USER_NAME} wheel && chown ${USER_NAME}:wheel /var/spool/cron/crontabs && chown root:${USER_NAME} /var/spool/cron/crontabs/${USER_NAME}

# clean
RUN apk del tzdata libjpeg-turbo-dev libpng-dev freetype-dev libwebp-dev libxpm-dev $PHPIZE_DEPS \
    && rm -rf /var/cache/apk/* /tmp/*

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]