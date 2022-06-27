<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class UserAgentSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelRequest(RequestEvent $requestEvent)
    {
        $request = $requestEvent->getRequest();

        $userAgent = $request->headers->get('User-Agent');
        $this->logger->info(sprintf('The User-Agent is "%s"', $userAgent));
    }

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => 'onKernelRequest'
        ];
    }
}