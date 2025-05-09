up:
	docker-compose -f infra/docker-compose.yaml up -d --build

down:
	docker-compose -f infra/docker-compose.yaml down

logs:
	docker-compose -f infra/docker-compose.yaml logs -f

bash:
	docker-compose -f infra/docker-compose.yaml exec symfony bash

composer-install:
	docker-compose -f infra/docker-compose.yaml exec symfony composer install