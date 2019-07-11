
env:
	cp .env.dist .env
up:
	docker-compose up -d

down:
	docker-compose down

first: env up

rebuild: down up

setup:
	docker exec codevate-app bin/console doc:sch:create
	docker exec codevate-app bin/console app:create-user Testuser test@user.com 123456

queue:
	docker exec codevate-app bin/console messenger:consume async -vv