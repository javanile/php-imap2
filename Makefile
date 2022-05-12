
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



test-open:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testOpenAndClose

test-append:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testAppend

test-list:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testList

test-xoauth:
	@docker-compose run --rm phpunit tests --filter XoauthTest
