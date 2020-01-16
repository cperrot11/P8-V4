EXEC_PHP = php
CONSOLE  = $(EXEC_PHP) bin/console
TEST     = $(EXEC_PHP) bin/phpunit
COMPOSER = composer
SYMFONY  = symfony

.DEFAULT_GOAL := help

.PHONY: help reload-db-test coverage testfunc testcov

help:  ## Outputs this help screen
	@grep -h -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

## —— Composer  ————————————————————————————————————————————————————————————
coin: composer.lock ## Install vendors according to the current composer.lock file
	$(COMPOSER) install

coup: composer.json ## Update vendors according to the composer.json file
	$(COMPOSER) update

## —— Symfony ———————————————————————————————————————————————————————————————
cacl: ## Clear the cache.
	$(SYMFONY) c:c

capu: ## Purge cache and logs
	rm -rf var/cache/* var/logs/*

seon: symfony ## Serve the application with HTTPS support
	$(SYMFONY) serve -d

seof: symfony ## Stop the web server
	$(SYMFONY) server:stop


## ——  Database  ————————————————————————————————————————————————————————————
tedb: ## Instal database and fixtures for test
	$(CONSOLE) doctrine:database:drop --force --env=test
	$(CONSOLE) doctrine:database:create --if-not-exists --env=test
	$(CONSOLE) doctrine:schema:create --env=test
	$(CONSOLE) doctrine:fixtures:load --no-interaction --env=test

## ——  Test  ————————————————————————————————————————————————————————————
te: teun tefu  ## Run all the tests

teun: ## run the units tests
	$(TEST) --filter TaskTest
	$(TEST) --filter UserTest

tefu:  ## run the functionals tests
	$(TEST) --filter UserControllerTest
	$(TEST) --filter TaskControllerTest

teco: reload-db-test ## Run the coverage test
	rm -rf var/data/*
	$(TEST) --coverage-html var/data
