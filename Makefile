dev development: # Build application for development
	@composer install --no-interaction

prod production: # Build application for production
	@composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

test: # Run coding standards/static analysis checks and tests
	@vendor/bin/php-cs-fixer fix --diff --dry-run \
		&& vendor/bin/phpstan analyze \
		&& vendor/bin/phpunit --coverage-text

coverage: # Generate an HTML coverage report
	@vendor/bin/phpunit --coverage-html .coverage
