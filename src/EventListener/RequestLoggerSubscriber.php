<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\EventListener;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Exception\CustomerNotFoundException;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\MessageBusInterface;
use ThreeBRS\SyliusAnalyticsPlugin\Message\LogVisitMessage;

class RequestLoggerSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MessageBusInterface $bus,
        private ChannelContextInterface $channelContext,
        private CustomerContextInterface $customerContext,
        private string $adminPath,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'onRequest'];
    }

    public function onRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$event->isMainRequest()) {
            return;
        }

        $path = $request->getPathInfo();

        if (
            str_starts_with($path, '/' . $this->adminPath) ||
            str_starts_with($path, '/_profiler') ||
            str_starts_with($path, '/_wdt') ||
            str_starts_with($path, '/media/cache')
        ) {
            return;
        }
        //to satisfy PHPStan
        $customerId = null;

        try {
            $customer = $this->customerContext->getCustomer();
            if ($customer instanceof CustomerInterface) {
                $id = $customer->getId();
                if (is_scalar($id) || (is_object($id) && method_exists($id, '__toString'))) {
                    $customerId = (string) $id;
                }
            }
        } catch (CustomerNotFoundException $e) {
            $customerId = null;
        }

        $routeAttr = $request->attributes->get('_route');
        $route = is_string($routeAttr) ? $routeAttr : 'unknown';

        $channelCode = (string) $this->channelContext->getChannel()->getCode();

        $sessionId = $request->getSession()->getId();

        $this->bus->dispatch(new LogVisitMessage(
            $request->getUri(),
            $route,
            $channelCode,
            $customerId,
            $sessionId,
            $request->getClientIp(),
            $request->headers->get('User-Agent'),
        ));
    }
}
