<?php

namespace App\EventSubscriber;

use App\Service\PrometheusMetrics;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class MetricsSubscriber implements EventSubscriberInterface
{
    private PrometheusMetrics $metrics;
    private array $startTimes = [];

    public function __construct(PrometheusMetrics $metrics)
    {
        $this->metrics = $metrics;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::TERMINATE => 'onKernelTerminate',
        ];
    }

    public function onKernelRequest(): void
    {
        $this->startTimes[] = microtime(true);
    }

    public function onKernelTerminate(TerminateEvent $event): void
    {
        $startTime = array_pop($this->startTimes);
        if ($startTime === null) {
            return;
        }

        $duration = microtime(true) - $startTime;
        $this->metrics->recordRequest(
            $event->getRequest(),
            $event->getResponse(),
            $duration
        );
    }
} 