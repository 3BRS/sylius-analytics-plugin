<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\MessageHandler;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Clock\ClockInterface;
use ThreeBRS\SyliusAnalyticsPlugin\Entity\RequestLog;
use ThreeBRS\SyliusAnalyticsPlugin\Message\LogVisitMessage;

#[AsMessageHandler]
final class LogVisitMessageHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ChannelRepositoryInterface $channelRepository,
        private CustomerRepositoryInterface $customerRepository,
        private ClockInterface $clock,
    ) {}

    public function __invoke(LogVisitMessage $message): void
    {
        $channel = $this->channelRepository->findOneByCode($message->channel);

        if (null === $channel) {
            throw new \RuntimeException(sprintf('Channel with code "%s" not found.', $message->channel));
        }

        $customer = null;
        if ($message->customer !== null) {
            $customer = $this->customerRepository->find($message->customer);
        }

        $log = new RequestLog();
        $log->setUrl($message->url);
        $log->setRouteName($message->route);
        $log->setChannel($channel);
        $log->setCustomer($customer);
        $log->setSessionId($message->sessionId);
        $log->setIpAddress($message->ip);
        $log->setUserAgent($message->userAgent);
        $log->setCreatedAt($this->clock->now());

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}
