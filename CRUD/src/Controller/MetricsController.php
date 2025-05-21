<?php

namespace App\Controller;

use Prometheus\CollectorRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MetricsController extends AbstractController
{
    private CollectorRegistry $registry;

    public function __construct(CollectorRegistry $registry)
    {
        $this->registry = $registry;
    }

    #[Route('/metrics', name: 'app_metrics', methods: ['GET'])]
    public function index(): Response
    {
        $renderer = new \Prometheus\RenderTextFormat();
        $result = $renderer->render($this->registry->getMetricFamilySamples());

        return new Response($result, 200, [
            'Content-Type' => 'text/plain; version=0.0.4',
        ]);
    }
} 