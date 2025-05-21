<?php

namespace App\Controller;

use App\Service\PrometheusMetrics;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MetricsController extends AbstractController
{
    private PrometheusMetrics $metrics;

    public function __construct(PrometheusMetrics $metrics)
    {
        $this->metrics = $metrics;
    }

    #[Route('/metrics', name: 'app_metrics')]
    public function index(): Response
    {
        $renderer = new \Prometheus\RenderTextFormat();
        return new Response(
            $renderer->render($this->metrics->getRegistry()->getMetricFamilySamples()),
            Response::HTTP_OK,
            ['Content-Type' => 'text/plain; version=0.0.4']
        );
    }
} 