SHELL=/bin/sh

ifndef COMPOSE_PROJECT_NAME
	include .env
	ifneq ("$(wildcard .env.local)","")
		include .env.local
	endif
endif


DATABASE=${COMPOSE_PROJECT_NAME}.database.postgres
DATABASE_SLAVE=${COMPOSE_PROJECT_NAME}.database.mariadb
APP=${COMPOSE_PROJECT_NAME}.application


logs:
	docker compose logs -f --tail 100
up:
	docker compose up -d --force-recreate
ps:
	docker compose ps
restart:
	docker compose restart
stop:
	docker compose stop
images:
	docker compose images
start:
	docker compose start
down:
	docker compose down
top:
	docker compose top
kill:
	docker compose kill
ls:
	docker compose ls -a


install: composer migrate ps

re-fresh: clean fresh
clean:
	docker compose down -v --rmi local --remove-orphans
fresh:
	docker --debug compose build --no-cache --progress=plain
	docker --debug compose up -d --force-recreate

composer: composer-install composer-clear-cache
composer-install:
	docker exec -it $(APP) $(SHELL) -c "composer install --no-interaction -vvv"
composer-remove-vendor:
	docker exec -it -u root $(APP) $(SHELL) -c "rm -rf vendor/"
composer-clear-cache:
	docker exec -it $(APP) $(SHELL) -c "composer clear-cache --no-interaction -vvv"
composer-re: composer-remove-vendor composer-clear-cache composer
composer-rebuild:
	docker exec -it $(APP) $(SHELL) -c "composer clear-cache --no-interaction -v"
	docker exec -it $(APP) $(SHELL) -c "composer install --no-interaction --optimize-autoloader -v"
composer-production:
	docker exec -it $(APP) $(SHELL) -c "composer install --no-dev --no-scripts --optimize-autoloader --prefer-dist --no-interaction --no-progress"
composer-production-rebuild: composer-remove-vendor composer-production
npm: npm-install npm-cache-clean
npm-install:
	docker exec -it $(APP) $(SHELL) -c "npm install --verbose"
npm-cache-clean:
	docker exec -it $(APP) $(SHELL) -c "npm cache clean --force --verbose"
mpm-remove-node-modules:
	docker exec -it -u root $(APP) $(SHELL) -c "rm -rf node_modules/"
npm-re: mpm-remove-node-modules npm-cache-clean npm
npm-run-build-production:
	docker exec -it $(APP) $(SHELL) -c "npm run build --verbose"


app:
	docker exec -it $(APP) $(SHELL)
app-none-root:
	docker exec -it -u www-data $(APP) $(SHELL)
app-root:
	docker exec -it -u root $(APP) $(SHELL)
app-log:
	docker logs -f --tail 100 $(APP)
app-cron-log:
	docker exec -it $(APP) $(SHELL) -c "tail -n 20 -f /var/log/cron.log"
database:
	docker exec -it $(DATABASE) $(SHELL)
database-cli:
	docker exec -it $(DATABASE) $(SHELL) -c "psql -U $(POSTGRES_USER) -W -d $(POSTGRES_DB)"
database-slave-cli:
	docker exec -it $(DATABASE) $(SHELL) -c "mysql -u $(MYSQL_USER) -p$(MYSQL_PASSWORD) -P $(MYSQL_PORT) $(MYSQL_DATABASE)"
database-dump-production:
	ssh $(SSH) "docker exec -i ns3.database.postgres pg_dump -F p -U postgres -W ns3_db" > ./dump_ns3.sql
database-import:
	docker exec -it $(DATABASE) $(SHELL) -c "psql -U $(POSTGRES_USER) -W -d $(POSTGRES_DB) -f /var/lib/postgresql/data/dump_ns3.sql"
database-inside-container:
	psql -U postgres -W -d ns3_db -f /var/lib/postgresql/data/dump_ns3.sql


migrate:
	docker exec -it $(APP) $(SHELL) -c "php bin/console doctrine:migrations:migrate --no-interaction --all-or-nothing -vvv"
migrate-status:
	docker exec -it $(APP) $(SHELL) -c "php bin/console doctrine:migrations:status -vvv"
migrate-list:
	docker exec -it $(APP) $(SHELL) -c "php bin/console doctrine:migrations:list -vvv"
cc:
	docker exec -it $(APP) $(SHELL) -c "php bin/console cache:clear -vvv"
about:
	docker exec -it $(APP) $(SHELL) -c "php bin/console about -vvv"
router:
	docker exec -it $(APP) $(SHELL) -c "php bin/console debug:router -vvv"


log-application-container:
	ssh $(SSH) "docker logs -f --tail=50 ns3.application"
log-prod-auth:
	ssh $(SSH) "docker exec -i ns3.application tail -n 20 -f var/log/auth.log"
log-cron: app-cron-log