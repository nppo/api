init: init-ide init-php init-git init-env

init-git:
	cp .dev/git/hooks/prepare-commit-msg .git/hooks/prepare-commit-msg

init-env:
	cp .dev/envs/.env.local.example .env

init-ide:
	cp .dev/IDE/.editorconfig .editorconfig

init-php:
	cp .dev/PHP/* .

init-oauth:
	php artisan passport:client --public --name=surapp_frontend_local --redirect_uri="http://localhost:3000/login" --no-interaction --no-ansi -vvv
