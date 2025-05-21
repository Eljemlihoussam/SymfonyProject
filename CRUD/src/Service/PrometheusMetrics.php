<?php

namespace App\Service;

use Prometheus\CollectorRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PrometheusMetrics
{
    private CollectorRegistry $registry;
    private $requestCounter;
    private $requestDuration;

    public function __construct(CollectorRegistry $registry)
    {
        $this->registry = $registry;
        
        $this->requestCounter = $this->registry->getOrRegisterCounter(
            'symfony',
            'http_requests_total',
            'Total number of HTTP requests',
            ['method', 'route', 'status']
        );

        $this->requestDuration = $this->registry->getOrRegisterHistogram(
            'symfony',
            'http_request_duration_seconds',
            'HTTP request duration in seconds',
            ['method', 'route'],
            [0.1, 0.5, 1, 2.5, 5]
        );
    }

    public function recordRequest(Request $request, Response $response, float $duration): void
    {
        $route = $request->attributes->get('_route', 'unknown');
        $method = $request->getMethod();
        $status = $response->getStatusCode();

        $this->requestCounter->inc([
            'method' => $method,
            'route' => $route,
            'status' => (string) $status
        ]);

        $this->requestDuration->observe($duration, [
            'method' => $method,
            'route' => $route
        ]);
    }
} 