
bash:
	docker-compose run --rm php bash

build:
	docker-compose build

install:
	docker-compose run --rm composer install

dump-autoload:
	docker-compose run --rm composer dump-autoload


test:
	docker-compose run --rm phpunit tests

test-imap:
	docker-compose run --rm phpunit tests



test-append:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testAppend

