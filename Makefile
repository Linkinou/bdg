APP = docker-compose exec php
YARN = docker-compose run --rm yarn
CONSOLE = bin/console
COMPOSER = composer

prepare-env:
	mv docker-compose.test.yml docker-compose.yml && mv app/.env.test app/.env

prepare-ci: prepare-env
	docker-compose build && docker-compose up -d

assets:
	$(APP) $(CONSOLE) assets:install

install:
	$(APP) $(COMPOSER) install -n

yarn-install:
	$(YARN) yarn install

yarn-add:
	$(YARN) yarn add $(lib)

npm-install:
	$(YARN) npm install

encore:
	$(YARN) yarn encore dev

encore-watch:
	$(YARN) yarn encore dev --watch

encore-prod:
	$(YARN) yarn encore production

cache-clear:
	$(APP) $(CONSOLE) cache:clear --no-warmup || rm -rf var/cache/*

migrations:
	$(APP) $(CONSOLE) doctrine:migrations:migrate -n

migrations-diff:
	$(APP) $(CONSOLE) make:migration

fix-permissions:
	sudo chown -R linkinou:linkinou ./app/var/cache/* ./app/var/log/* ./app/src/Migrations/*
	sudo chmod -R 777 ./app/var/log/* ./app/var/cache/*

fix-images-permission:
	docker-compose exec app chown -R www-data web/bundles/front/images

fixtures:
	$(APP) $(CONSOLE) hautelook:fixtures:load --purge-with-truncate -n

fixtures-test:
	$(APP) $(CONSOLE) hautelook:fixtures:load --env=test --purge-with-truncate

show-logs:
	tail ./app/var/log/*

watch-logs:
	tail -f ./app/var/log/*

test:
	$(APP) ./bin/phpunit tests

wait-mysql:
	echo "Let's wait for MySQL to get ready..." && sleep 15

launch-ci: wait-mysql install assets migrations fixtures npm-install encore test