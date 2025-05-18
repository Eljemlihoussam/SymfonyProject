#!/bin/bash
 
# Créer un conteneur temporaire pour copier la configuration
docker run --rm -v crud_prometheus_config:/etc/prometheus -v $(pwd)/docker/prometheus/prometheus.yml:/prometheus.yml prom/prometheus:latest sh -c "cp /prometheus.yml /etc/prometheus/prometheus.yml" 