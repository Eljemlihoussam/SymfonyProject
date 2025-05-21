<?php

namespace App\Service;

use Prometheus\CollectorRegistry;
use Prometheus\Storage\Redis;
use Symfony\Component\HttpFoundation\RequestStack;

class PrometheusMetrics
{
    private CollectorRegistry $registry;
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        Redis::setDefaultOptions([
            'host' => $_ENV['REDIS_HOST'] ?? 'redis',
            'port' => $_ENV['REDIS_PORT'] ?? 6379,
        ]);
        
        $this->registry = new CollectorRegistry();
        $this->requestStack = $requestStack;

        // Initialiser les métriques
        $this->initializeMetrics();
    }

    private function initializeMetrics(): void
    {
        // Métriques pour les requêtes HTTP
        $this->registry->getOrRegisterCounter('symfony', 'http_requests_total', 'Total des requêtes HTTP', ['method', 'route', 'status']);
        $this->registry->getOrRegisterHistogram('symfony', 'http_request_duration_seconds', 'Durée des requêtes HTTP', ['method', 'route'], [0.1, 0.5, 1, 2, 5]);

        // Métriques pour la base de données
        $this->registry->getOrRegisterCounter('symfony', 'database_queries_total', 'Total des requêtes base de données', ['type']);
        $this->registry->getOrRegisterHistogram('symfony', 'database_query_duration_seconds', 'Durée des requêtes base de données', ['type'], [0.01, 0.05, 0.1, 0.5, 1]);

        // Métriques pour les entités
        $this->registry->getOrRegisterGauge('symfony', 'clients_total', 'Nombre total de clients');
        $this->registry->getOrRegisterGauge('symfony', 'factures_total', 'Nombre total de factures');
        $this->registry->getOrRegisterGauge('symfony', 'users_total', 'Nombre total d\'utilisateurs');

        // Métriques pour le cache
        $this->registry->getOrRegisterCounter('symfony', 'cache_operations_total', 'Total des opérations de cache', ['operation', 'result']);
    }

    public function recordHttpRequest(string $method, string $route, int $status, float $duration): void
    {
        $this->registry->getCounter('symfony', 'http_requests_total', 'Total des requêtes HTTP', ['method', 'route', 'status'])
            ->inc([$method, $route, (string)$status]);
        
        $this->registry->getHistogram('symfony', 'http_request_duration_seconds', 'Durée des requêtes HTTP', ['method', 'route'])
            ->observe($duration, [$method, $route]);
    }

    public function recordDatabaseQuery(string $type, float $duration): void
    {
        $this->registry->getCounter('symfony', 'database_queries_total', 'Total des requêtes base de données', ['type'])
            ->inc([$type]);
        
        $this->registry->getHistogram('symfony', 'database_query_duration_seconds', 'Durée des requêtes base de données', ['type'])
            ->observe($duration, [$type]);
    }

    public function updateEntityCounts(int $clients, int $factures, int $users): void
    {
        $this->registry->getGauge('symfony', 'clients_total', 'Nombre total de clients')
            ->set($clients);
        
        $this->registry->getGauge('symfony', 'factures_total', 'Nombre total de factures')
            ->set($factures);
        
        $this->registry->getGauge('symfony', 'users_total', 'Nombre total d\'utilisateurs')
            ->set($users);
    }

    public function recordCacheOperation(string $operation, string $result): void
    {
        $this->registry->getCounter('symfony', 'cache_operations_total', 'Total des opérations de cache', ['operation', 'result'])
            ->inc([$operation, $result]);
    }

    public function getRegistry(): CollectorRegistry
    {
        return $this->registry;
    }
} 