
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

test-check:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testCheck

test-append:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testAppend

test-list:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testList

test-delete:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testDelete

test-fetch-body:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testFetchBody

test-xoauth:
	@docker-compose run --rm phpunit tests --filter XoauthTest
