path = docker

start: 
	if [$$(docker ps -aq)]; then docker container stop $$(docker ps -aq); fi
	docker-compose up -d;

stop: 
	docker-compose down

build:
	docker container stop $$(docker ps -aq);
	docker-compose build; docker-compose up -d;
	composer i; npm i; npm run build;
bash:
	docker exec -it openrpg_app_1 bash

fix:
	tools/php-cs-fixer/vendor/bin/php-cs-fixer fix -vvv

phpstan:
	vendor/bin/phpstan analyse

test:
	vendor/bin/phpunit