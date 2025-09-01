FROM public.ecr.aws/lambda/nodejs:18 AS node

FROM docker.io/bref/php-84-fpm-dev:2.3.30

COPY --from=docker.io/composer:2.8.11 /usr/bin/composer /usr/bin/

RUN curl -sL https://github.com/serverless/serverless/releases/download/v3.39.0/serverless-linux-x64 -o /usr/bin/serverless \
 && chmod +x /usr/bin/serverless

ENV PATH="$PATH:/var/lang/bin"
COPY --from=node /var/lang /var/lang
