FROM docker.io/bref/php-83-fpm-dev:2.3.3

COPY --from=docker.io/composer:2.7.7 /usr/bin/composer /usr/bin/

RUN curl -sL https://github.com/serverless/serverless/releases/download/v3.39.0/serverless-linux-x64 -o /usr/bin/serverless \
 && chmod +x /usr/bin/serverless
