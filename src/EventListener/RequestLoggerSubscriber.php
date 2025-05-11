<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use ThreeBRS\SyliusAnalyticsPlugin\Message\LogVisitMessage;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Core\Exception\CustomerNotFoundException;



final class RequestLoggerSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MessageBusInterface $bus,
        private ChannelContextInterface $channelContext,
        private CustomerContextInterface $customerContext,
        private RequestStack $requestStack,
        private string $adminPath
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'onRequest'];
    }

    public function onRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
    
        // Skip subrequests (e.g., fragments)
        if (!$event->isMainRequest()) {
            return;
        }
    
        $path = $request->getPathInfo();
    
        // Skip admin, profiler, WDT, media
        if (
            str_starts_with($path, '/' . $this->adminPath) ||
            str_starts_with($path, '/_profiler') ||
            str_starts_with($path, '/_wdt') ||
            str_starts_with($path, '/media/cache')
        ) {
            return;
        }
    
        // Handle customer context safely
        $customerId = null;
        try {
            $customer = $this->customerContext->getCustomer();
            if ($customer instanceof CustomerInterface) {
                $customerId = $customer->getId();
            }
        } catch (CustomerNotFoundException $e) {
            $customerId = null;
        }
    
        $this->bus->dispatch(new LogVisitMessage(
            $request->getUri(),
            $request->attributes->get('_route') ?? 'unknown',
            $this->channelContext->getChannel()?->getCode(),
            $customerId,
            $request->getSession()?->getId(),
            $request->getClientIp(),
            $request->headers->get('User-Agent'),
        ));
    }
}
