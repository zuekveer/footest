# Base PHP-FPM image
FROM php:8.2-fpm

# Install PHP dependencies and basic tools
RUN apt-get update \
    && apt-get install -y \
        libpq-dev \
        nano \
        git \
        curl \
        zlib1g-dev \
        libzip-dev \
        unzip \
        postgresql-client \
        build-essential \
        libssl-dev \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && pecl install apcu \
    && docker-php-ext-enable apcu

# Install Composer
ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer:2.8.0 /usr/bin/composer /usr/bin/composer

# Create and use a user with the same UID and GID as the host user
ARG UID
ARG GID
RUN groupadd -g ${GID} appgroup && \
    useradd -u ${UID} -g appgroup -m appuser

# Set working directory
WORKDIR /app

# Copy application and set correct permissions
COPY --chown=appuser:appgroup . /app

# Switch to the new user
USER appuser

# Command to run PHP-FPM
CMD ["php-fpm"]
