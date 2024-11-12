build:
	docker compose up --build

rebuild:
	docker compose down && docker compose up -d --build

start:
	docker compose up -d

stop:
	docker compose down

restart:
	docker compose down && docker compose up -d

php:
	docker compose exec php bash

logs_db:
	docker compose logs db

logs_php:
	docker compose logs php

migrate:
	docker compose exec php php artisan migrate

update:
	docker compose exec php composer update

phpunit:
	docker compose exec php php vendor/bin/phpunit
