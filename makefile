include ./.dev/docker/makefile
include ./.dev/envs/makefile
include ./.dev/php/makefile

init: init-ide init-git docker-init php-init env-local

init-git:
	cp .dev/git/hooks/prepare-commit-msg .git/hooks/prepare-commit-msg

init-ide:
	cp .dev/IDE/.editorconfig .editorconfig

up: env-local docker-up

in: docker-in

mfs:
	${DOCKER_COMPOSE_COMMAND} exec php-fpm php artisan migrate:fresh --seed --no-interaction -vvv
