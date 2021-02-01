init: init-git init-env app-install init-app init-db clear

init-git:
	cp .dev/git/hooks/prepare-commit-msg .git/hooks/prepare-commit-msg

init-env:
	cp .dev/envs/.env.local.example .env

init-app:
	php artisan key:generate

init-db:
	php artisan migrate:fresh --seed

clear:
	php artisan clear

app-install:
	composer install

init-pipelines:
	-git clone ssh://git@bitbucket.org/way2dev/bitbucket-pipelines --branch v3.0.2 --single-branch
	cd bitbucket-pipelines && composer install --no-interaction --no-progress --no-ansi --prefer-dist && cd ..
	cp .build/pipelines/* .

run-pipelines:
	-./bitbucket-pipelines/prepare.sh
	rm .env
	cp .build/pipelines/* .
	cp .dev/envs/.env.testing.example .env.pipeline
	cp .dev/envs/.env.testing.example .env
	./bitbucket-pipelines/php/composer.sh
	./bitbucket-pipelines/php/validation.sh
	./bitbucket-pipelines/js/validation.sh
	./bitbucket-pipelines/php/security.sh
	./bitbucket-pipelines/php/compatibility.sh
	./bitbucket-pipelines/php/analysis.sh
	php artisan migrate:fresh --env=testing
	./bitbucket-pipelines/php/bootstrap.sh
	./bitbucket-pipelines/js/tests.sh
	./bitbucket-pipelines/js/build.sh
	./bitbucket-pipelines/php/tests.sh
	./bitbucket-pipelines/php/seeding.sh
	./bitbucket-pipelines/php/translations.sh
	./bitbucket-pipelines/browser/tests.sh
	cp .env.bak .env

pipelines: init-pipelines run-pipelines

coverage:
	vendor/bin/phpunit --coverage-html .temp/report
