.PHONY: greeting

greeting:
	@echo "\nBooks API\n"
	@echo "Available commands:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf " • \033[36m%-25s\033[0m %s\n", $$1, $$2}'
	@echo

build-and-run: ## Build and run DEV docker containers
	@docker compose up --build -d;
	@docker exec books-api sh -c 'if [ -z "$$(grep "^APP_KEY=" /var/www/html/.env | cut -d "=" -f2)" ]; then php artisan key:generate; fi';
	@docker exec books-api php artisan l5-swagger:generate;

stop: ## Stop all DEV docker containers
	@docker compose down;

test: ## Run all tests
	@docker exec books-api sh -c 'php artisan test';

pint-test: ## Run Pint test
	@docker exec books-api sh -c './vendor/bin/pint --test';

pint-fix: ## Run Pint fix
	@docker exec books-api sh -c './vendor/bin/pint';