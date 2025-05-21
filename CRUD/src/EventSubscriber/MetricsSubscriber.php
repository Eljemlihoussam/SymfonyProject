<?php

namespace App\EventSubscriber;

use App\Service\PrometheusMetrics;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class MetricsSubscriber implements EventSubscriberInterface
{
    private PrometheusMetrics $metrics;
    private EntityManagerInterface $entityManager;
    private float $startTime;

    public function __construct(PrometheusMetrics $metrics, EntityManagerInterface $entityManager)
    {
        $this->metrics = $metrics;
        $this->entityManager = $entityManager;
        $this->startTime = microtime(true);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::TERMINATE => 'onKernelTerminate',
        ];
    }

    public function onKernelTerminate(TerminateEvent $event): void
    {
        $request = $event->getRequest();
        $response = $event->getResponse();
        
        // Enregistrer les métriques HTTP
        $duration = microtime(true) - $this->startTime;
        $this->metrics->recordHttpRequest(
            $request->getMethod(),
            $request->attributes->get('_route', 'unknown'),
            $response->getStatusCode(),
            $duration
        );

        // Mettre à jour les compteurs d'entités
        $this->updateEntityCounts();
    }

    private function updateEntityCounts(): void
    {
        $clientsCount = $this->entityManager->getRepository('App\Entity\Client')->count([]);
        $facturesCount = $this->entityManager->getRepository('App\Entity\Facture')->count([]);
        $usersCount = $this->entityManager->getRepository('App\Entity\User')->count([]);

        $this->metrics->updateEntityCounts($clientsCount, $facturesCount, $usersCount);
    }
} 