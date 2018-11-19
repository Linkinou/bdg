APP = docker-compose exec php
CONSOLE = bin/console
COMPOSER = composer

assets:
	$(APP) $(CONSOLE) assets:install

install:
	$(APP) $(COMPOSER) install

cache-clear:
	$(APP) $(CONSOLE) cache:clear --no-warmup || rm -rf var/cache/*

migrations:
	$(APP) $(CONSOLE) doctrine:migrations:migrate

migrations-diff:
	$(APP) $(CONSOLE) doctrine:migrations:diff

fix-permissions:
	sudo chown -R linkinou:linkinou ./var/cache/* ./var/logs/* ./app/DoctrineMigrations/*
	sudo chmod -R 777 ./var/logs/* ./var/cache/*

fix-images-permission:
	docker-compose exec app chown -R www-data web/bundles/front/images

fixtures:
	$(APP) $(CONSOLE) hautelook:fixtures:load --purge-with-truncate -vvv
