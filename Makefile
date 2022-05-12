
install:
	docker-compose run --rm composer install

test:
	docker-compose run --rm phpunit tests





