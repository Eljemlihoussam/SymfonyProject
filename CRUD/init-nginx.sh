#!/bin/bash
 
# Créer un conteneur temporaire pour copier la configuration
docker run --rm -v crud_nginx_config:/etc/nginx/conf.d -v $(pwd)/docker/nginx/default.conf:/default.conf nginx:alpine sh -c "cp /default.conf /etc/nginx/conf.d/default.conf" 