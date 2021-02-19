#!/usr/bin/env bash

export COMPOSER_OPTIONS=""
export PHP_CS_FIXER_FOLDERS="app tests database routes"
export PHP_CS_FIXER_EXIT=true
export PHPMD_FOLDERS="app,database,routes"
export PHPMD_EXIT=true
export PHPCPD_FOLDERS="app database routes"
export PHPCPD_EXIT=false
export PHPMND_FOLDERS="app tests database routes"
export PHPMND_EXIT=false
export PHPCF_FOLDERS="app tests database config routes"
export PHPCF_EXIT=true
export PHP_DOC_CHECK_EXIT=false
export LARASTAN_EXIT=true
export PHPMETRICS_FOLDERS="app,tests,database,routes,composer.json,composer.lock"
export PHPUNIT_COVERAGE_HTML="--coverage-html build/tests/phpunit"
export PHPUNIT_JUNIT_LOG="--log-junit build/tests/phpunit/junit.xml"
export PHPUNIT_EXIT=true
export PHP_DEBUG="php -d pcov.enabled=1"
export BROWSER_TESTS_EXIT=true
export SENTRY_CLI_DIRECTORY=
export SECURITY_CHECKER_EXIT=false
export PHPCS_SECURITY_EXIT=true
export PHPLOC_FOLDERS="app tests database routes"
