.PHONY: *

COMPOSE := docker compose -f docker/compose.yaml -p f1-picks
APP := $(COMPOSE) exec -T app

start: up composer db

up:
	$(COMPOSE) up -d --build --force-recreate

stop:
	$(COMPOSE) down -v --remove-orphans

restart: stop start

composer:
	$(APP) composer install --no-interaction --no-ansi

db:
	$(APP) php artisan migrate:refresh --seed

clean:
	$(APP) rm -fr /tmp/storage

build:
	$(APP) composer install --no-dev --no-interaction --no-ansi --classmap-authoritative --no-scripts
	$(APP) rm -fr bootstrap/cache
	$(APP) mkdir bootstrap/cache
	$(APP) sh -c "APP_ENV=production php artisan config:clear"

shell:
	$(COMPOSE) exec app sh

open:
	open http://localhost:8000/
