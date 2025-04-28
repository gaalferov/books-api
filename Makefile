.PHONY: greeting

greeting:
	@echo "\nBooks API\n"
	@echo "Available commands:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf " • \033[36m%-25s\033[0m %s\n", $$1, $$2}'
	@echo

run: stop ## Run DEV docker containers
	@docker compose --build -d;

stop: ## Stop all DEV docker containers
	@docker compose down;