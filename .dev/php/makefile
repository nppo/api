php-init:
	cp ./.dev/php/phpunit.xml .
	cp ./.dev/php/phpstan.neon .

php-lint-stan: php-init
	vendor/bin/phpstan analyse --memory-limit=1G
