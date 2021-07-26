include ./.dev/docker/makefile
include ./.dev/envs/makefile

init: init-ide init-php init-git init-env docker-init

init-git:
	cp .dev/git/hooks/prepare-commit-msg .git/hooks/prepare-commit-msg

init-env:
	cp .dev/envs/.env.local.example .env

init-ide:
	cp .dev/IDE/.editorconfig .editorconfig

init-php:
	cp .dev/PHP/* .

up: env-local docker-up

in: docker-in

mfs:
	${DOCKER_COMPOSE_COMMAND} exec php-fpm php artisan migrate:fresh --seed --no-interaction -vvv
