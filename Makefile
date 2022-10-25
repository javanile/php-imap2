
bash:
	@docker-compose run --rm php bash

build:
	@docker-compose build

install:
	@docker-compose run --rm composer install

update:
	@docker-compose run --rm composer update

dump-autoload:
	@docker-compose run --rm composer dump-autoload

imap2-coverage:
	@docker-compose run --rm imap2 ./vendor/bin/phpunit tests --coverage-html docs/coverage

imap2-test:
	@docker-compose run --rm imap2 ./vendor/bin/phpunit tests --stop-on-failure

coverage:
	@docker-compose run --rm php ./vendor/bin/phpunit tests/ErrorsTest.php --coverage-html docs/coverage

release:
	git add .
	git commit -am "Test CI"
	git push


## =======
## Develop
## =======
google-access-token-link:
	@open "https://accounts.google.com/o/oauth2/v2/auth/oauthchooseaccount?redirect_uri=https%3A%2F%2Fdevelopers.google.com%2Foauthplayground&prompt=consent&response_type=code&client_id=407408718192.apps.googleusercontent.com&scope=https%3A%2F%2Fmail.google.com%2F&access_type=offline&flowName=GeneralOAuthFlow"

google-access-token:
	@bash contrib/google-access-token.sh

refresh-access-token:
	@bash contrib/refresh-access-token.sh

## =====
## Tests
## =====
test:
	@docker-compose run --rm phpunit tests --stop-on-failure --verbose

test-open:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testOpenAndClose

test-alerts:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testAlerts

test-last-error:
	@docker-compose run --rm phpunit tests --filter ErrorsTest::testLastError

test-check:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testCheck

test-status:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testStatus

test-mailbox-msg-info:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testMailboxMsgInfo

test-append:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testAppend

test-list:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testList

test-delete:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testDelete

test-fetch-body:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testFetchBody

test-fetch-overview:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testFetchOverview

test-uid:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testUid

test-create-mailbox:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testCreateMailbox

test-copy:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testCopy

test-move:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testMove

test-fetch-header:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testFetchHeader

test-fetch-structure:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testFetchStructure

test-header-info:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testHeaderInfo

test-headers:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testHeaders

test-num-msg:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testNumMsg

test-reopen:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testReopen

test-fetch-mime:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testFetchMime

test-ping:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testPing

test-get-mailboxes:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testGetMailboxes

test-delete-mailbox:
	@docker-compose run --rm phpunit tests --filter CompatibilityTest::testDeleteMailbox

test-body-structure:
	@docker-compose run --rm phpunit tests --filter BodyStructureTest::testFetchStructure

test-search:
	@docker-compose run --rm phpunit tests --filter SearchTest

test-timeout:
	@docker-compose run --rm phpunit tests --filter XoauthTest::testTimeout

test-xoauth:
	@docker-compose run --rm phpunit tests --filter XoauthTest

test-signatures:
	@docker-compose run --rm phpunit tests --filter SignaturesTest

test-polyfill:
	@docker-compose run --rm phpunit tests --filter PolyfillTest

test-parse-headers:
	@docker-compose run --rm phpunit tests --filter PolyfillTest::testRfc822ParseHeaders

test-parse-adrlist:
	@docker-compose run --rm phpunit tests --filter PolyfillTest::testRfc822ParseAdrList

test-special:
	@docker-compose run --rm phpunit tests --filter HeaderInfoTest::testSanitizeAddress

test-minimal:
	@docker-compose run --rm phpunit tests --filter MinimalTest

test-cleaning:
	@docker-compose run --rm phpunit tests --filter CleaningTest

test-retrofit:
	@docker-compose run --rm phpunit tests --filter RetrofitTest

test-errors:
	@docker-compose run --rm phpunit tests --filter ErrorsTest

## ======
## Legacy
## ======
legacy-last-error:
	@docker-compose run --rm php -f tests/legacy/last-error.php

## ====
## Diff
## ====
diff-last-error:
	@docker-compose run --rm php bash -c "php -f tests/legacy/last-error.php > tests/legacy/last-error.1.txt 2>&1"
	@docker-compose run --rm imap2 bash -c "php -f tests/legacy/last-error.php > tests/legacy/last-error.2.txt 2>&1"
	@docker-compose run --rm imap2 bash -c "chmod 777 -R tests/legacy"

diff-fetch-body-error:
	@docker-compose run --rm php bash -c "php -f tests/legacy/fetch-body-error.php > tests/legacy/fetch-body-error.1.txt 2>&1"
	@docker-compose run --rm imap2 bash -c "php -f tests/legacy/fetch-body-error.php > tests/legacy/fetch-body-error.2.txt 2>&1"
	@docker-compose run --rm imap2 bash -c "chmod 777 -R tests/legacy"
