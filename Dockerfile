FROM php:8.3-cli

# unzip lets Composer extract packages
RUN apt-get update && apt-get install -y --no-install-recommends unzip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

EXPOSE 10000
CMD ["sh", "docker/start.sh"]
