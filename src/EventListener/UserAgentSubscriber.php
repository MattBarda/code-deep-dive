<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
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
        if (!$requestEvent->isMasterRequest()) {
            return;
        }

        $request = $requestEvent->getRequest();

//        $request->attributes->set('_controller', function($slug = null) {
//            dd($slug);
//            return new Response('I just took over the controller/');
//        });

        $userAgent = $request->headers->get('User-Agent');
        $this->logger->info(sprintf('The User-Agent is "%s"', $userAgent));

        $request->attributes->set('_isMac', $this->isMac($request));
    }

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => 'onKernelRequest'
        ];
    }

    private function isMac(Request $request)
    {
        if ($request->query->has('mac')) {
            return $request->query->getBoolean('mac');
        }

        $userAgent = $request->headers->get('User-Agent');

        return stripos($userAgent, 'Mac') !== false;
    }
}