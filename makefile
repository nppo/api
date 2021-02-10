init: init-ide init-php init-git init-env

init-git:
	cp .dev/git/hooks/prepare-commit-msg .git/hooks/prepare-commit-msg

init-env:
	cp .dev/envs/.env.local.example .env

init-ide:
	cp .dev/IDE/.editorconfig .editorconfig

init-php:
	cp .dev/PHP/* .
