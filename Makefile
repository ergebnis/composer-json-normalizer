.PHONY: coverage cs infection it stan test

it: cs stan test

coverage: vendor
	vendor/bin/phpunit --configuration=test/Unit/phpunit.xml --coverage-text

cs: vendor
	mkdir -p .build/php-cs-fixer
	vendor/bin/php-cs-fixer fix --config=.php_cs --diff --verbose

infection: vendor
	mkdir -p .build/infection
	vendor/bin/infection --ignore-msi-with-no-mutations --min-covered-msi=94 --min-msi=94

stan: vendor
	mkdir -p .build/phpstan
	vendor/bin/phpstan analyse --configuration=phpstan.neon

test: vendor
	vendor/bin/phpunit --configuration=test/AutoReview/phpunit.xml
	vendor/bin/phpunit --configuration=test/Unit/phpunit.xml

vendor: composer.json composer.lock
	composer validate
	composer install
