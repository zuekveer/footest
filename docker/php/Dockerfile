# Базовый образ PHP-FPM
FROM php:8.2-fpm

# Установка зависимостей для PHP и базовых инструментов
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

# Установка Node.js и npm для сборки фронтенда (Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Установка yarn (опционально, если нужно)
RUN npm install --global yarn

# Установка Composer
ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer:2.8.0 /usr/bin/composer /usr/bin/composer

# Создание и использование пользователя с UID и GID хоста
ARG UID
ARG GID
RUN groupadd -g ${GID} appgroup && \
    useradd -u ${UID} -g appgroup -m appuser

# Устанавливаем рабочую директорию
WORKDIR /app

# Копируем приложение и устанавливаем правильные права
COPY --chown=appuser:appgroup . /app

# Установка npm-зависимостей для Vite (если есть package.json)
RUN if [ -f package.json ]; then npm install; fi

# Переключаемся на нового пользователя
USER appuser

# Команда для запуска PHP-FPM
CMD ["php-fpm"]
