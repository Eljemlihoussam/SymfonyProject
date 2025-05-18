# Initialiser la configuration Prometheus
docker run --rm -v crud_prometheus_config:/etc/prometheus -v ${PWD}/docker/prometheus/prometheus.yml:/prometheus.yml prom/prometheus:latest /bin/sh -c "cp /prometheus.yml /etc/prometheus/prometheus.yml"

# Initialiser la configuration Nginx
docker run --rm -v crud_nginx_config:/etc/nginx/conf.d -v ${PWD}/docker/nginx/default.conf:/default.conf nginx:alpine /bin/sh -c "cp /default.conf /etc/nginx/conf.d/default.conf" 