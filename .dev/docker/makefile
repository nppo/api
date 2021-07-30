DOCKER_COMPOSE_COMMAND = docker-compose -f .dev/docker/docker-compose.yml -f .dev/docker/docker-compose.override.yml

docker-up: docker-init
	${DOCKER_COMPOSE_COMMAND} up

docker-up-fresh: docker-init
	${DOCKER_COMPOSE_COMMAND} up --force-recreate --build --remove-orphans --renew-anon-volumes

docker-down: docker-init
	${DOCKER_COMPOSE_COMMAND} down

docker-in: docker-init
	${DOCKER_COMPOSE_COMMAND} exec php-fpm bash

docker-init:
	sed 's/=/=$(shell basename `pwd`)/g' .dev/docker/resources/.env > .dev/docker/.env
	cp .dev/docker/resources/docker-compose.override.yml .dev/docker/docker-compose.override.yml
