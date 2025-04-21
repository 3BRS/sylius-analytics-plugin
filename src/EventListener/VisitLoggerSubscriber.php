<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use ThreeBRS\SyliusAnalyticsPlugin\Message\LogVisitMessage;
use Psr\Log\LoggerInterface;



final class VisitLoggerSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MessageBusInterface $bus,
        private ChannelContextInterface $channelContext,
        private CustomerContextInterface $customerContext,
        private RequestStack $requestStack,
        private LoggerInterface $logger, // ✅ Add logger

    ) {}

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'onRequest'];
    }

    public function onRequest(RequestEvent $event): void
    {
        $this->logger->info('✅ VisitLoggerSubscriber triggered');

        $request = $event->getRequest();

        // Ignore admin area + subrequests
        if (str_starts_with($request->getPathInfo(), '/admin') || !$event->isMainRequest()) {
            return;
        }
        $this->logger->info('📦 Dispatching LogVisitMessage for route: '.$request->attributes->get('_route'));


        $this->bus->dispatch(new LogVisitMessage(
            $request->getUri(),
            $request->attributes->get('_route') ?? 'unknown',
            $this->channelContext->getChannel()?->getCode(),
            $this->customerContext->getCustomer()?->getEmail(),
            $request->getSession()?->getId(),
            $request->getClientIp(),
            $request->headers->get('User-Agent'),
            new \DateTimeImmutable(),
        ));
    }
}
