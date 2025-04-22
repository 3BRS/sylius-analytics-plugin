<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\MessageHandler;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use ThreeBRS\SyliusAnalyticsPlugin\Entity\RequestLog;
use ThreeBRS\SyliusAnalyticsPlugin\Message\LogVisitMessage;

#[AsMessageHandler]
final class LogVisitMessageHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ChannelRepositoryInterface $channelRepository,
    ) {}

    public function __invoke(LogVisitMessage $message): void
    {
        $channel = $this->channelRepository->findOneByCode($message->channel);

        if (!$channel) {
            throw new \RuntimeException(sprintf('Channel with code "%s" not found.', $message->channel));
        }

        $log = new RequestLog();
        $log->setUrl($message->url);
        $log->setRouteName($message->route);
        $log->setChannel($channel); 
        $log->setCustomer($message->customer);
        $log->setSessionId($message->sessionId);
        $log->setIpAddress($message->ip);
        $log->setUserAgent($message->userAgent);
        $log->setCreatedAt($message->timestamp);

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}
