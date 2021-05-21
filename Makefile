all: help
#.PHONY: help status build comp-install build-container start stop shell cc

current-dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))

help: Makefile
	@sed -n 's/^##//p' $<

## status:	Show containers status
status:
	@docker-compose ps

## build:		Start container and install packages
build: build-container start comp-install

## build-container:Rebuild a container
build-container:
	@docker-compose up --build --force-recreate --no-deps -d

## start:		Start container
start:
	@docker-compose up -d

## stop:		Stop containers
stop:
	@docker-compose stop

## down:		Stop containers and remove stopped containers and any network created
down:
	@docker-compose down

## destroy:	Stop containers and remove its volumes (all information inside volumes will be lost)
destroy:
	@docker-compose down -v

## shell:		Interactive shell inside docker
shell:
	@docker-compose exec php_app_shoppingcart sh

## comp-install:	Install packages
comp-install:
	@docker-compose exec php_app_shoppingcart composer install

## cc:		Clear Symfony cache
cc:
	@docker-compose exec php_app_shoppingcart php apps/Symfony/bin/console cache:clear

## mysql:		Interactive shell inside mysql
mysql:
	@docker-compose exec mysql_shoppingcart sh

## mimi:		Execute all Doctrine migrations
mimi:
	@docker-compose exec php_app_shoppingcart php apps/Symfony/bin/console  doctrine:migrations:migrate --no-interaction

## unit-tests:	Execute all unit tests
unit-tests:
	@docker-compose exec php_app_shoppingcart php vendor/bin/phpunit tests/
