.PHONY: *
.DEFAULT_GOAL := help

SHELL := /bin/bash
COMPOSE := docker compose -f docker/compose.yaml -p f1-picks
APP := $(COMPOSE) exec -T app

##@ Setup

start: up composer npm db ## Start the application in development mode

restart: stop start ## Restart the application in development mode

up:
	cp app/.env.example app/.env
	$(COMPOSE) up -d --build --force-recreate

stop: ## Stop the application and clean up
	$(COMPOSE) down -v --remove-orphans

composer: ## Install Composer dependencies
	$(APP) composer install --no-interaction --no-ansi

npm: ## Install NPM dependencies
	$(APP) npm install

db: ## Run the database migrations and seed the database
	$(APP) php artisan migrate:refresh --seed

clean: ## Clean up the application
	$(APP) rm -fr /tmp/storage

##@ Testing/Linting

can-release: test lint ## Run all the same checks as CI to ensure code can be released

test: ## Run the test suite
	$(APP) php artisan test

test/%: ## Run the test suite with a filter
	$(APP) php artisan test --filter=$*

lint: ## Run the linting tools
	$(APP) vendor/bin/pint --test

fmt: format
format: ## Fix style related code violations
	$(APP) vendor/bin/pint

##@ Release

build: build-app build-assets ## Build for production

build-app: ## Build application for production
	$(APP) composer install --no-dev --no-interaction --no-ansi --classmap-authoritative --no-scripts
	$(APP) rm -fr bootstrap/cache
	$(APP) mkdir bootstrap/cache
	$(APP) sh -c "APP_ENV=production php artisan config:clear"

build-assets: ## Build assets for production
	$(APP) npm run build

##@ Running Instance

open: ## Open the website in the default browser
	open http://localhost:8000/

sh: shell
shell: ## Access a shell within the running container
	$(COMPOSE) exec app bash

shell/%: ## Run a command within the running container
	$(COMPOSE) exec app sh -c "$*"

logs: ## Tail the container logs
	$(COMPOSE) logs -f

ps: ## List the running containers
	$(COMPOSE) ps -a

help:
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z_\-\/\/]+:.*?##/ { printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)
