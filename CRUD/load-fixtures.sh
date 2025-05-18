#!/bin/bash
docker-compose exec php php bin/console doctrine:fixtures:load --no-interaction 