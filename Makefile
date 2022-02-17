path = docker

start: 
	for c in $$(sudo docker ps -q); do docker kill $$c; done
	docker-compose up -d;

stop: 
	for c in $$(sudo docker ps -q); do docker kill $$c; done

build:
	docker container stop $$(docker ps -aq);
	docker-compose build; docker-compose up -d;
	composer i; npm i; npm run build;
bash:
	docker exec -it openrpg_app_1 bash

fix:
	vendor/bin/php-cs-fixer fix -vvv --allow-risky=yes

phpstan:
	vendor/bin/phpstan analyse

test:
	vendor/bin/phpunit