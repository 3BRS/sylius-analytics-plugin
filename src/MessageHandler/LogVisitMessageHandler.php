<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\MessageHandler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use ThreeBRS\SyliusAnalyticsPlugin\Entity\VisitLog;
use ThreeBRS\SyliusAnalyticsPlugin\Message\LogVisitMessage;

#[AsMessageHandler]
final class LogVisitMessageHandler
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function __invoke(LogVisitMessage $message): void
    {
        $log = new VisitLog();
        $log->setUrl($message->url);
        $log->setRoute($message->route);
        $log->setChannel($message->channel);
        $log->setCustomer($message->customer);
        $log->setSessionId($message->sessionId);
        $log->setIp($message->ip);
        $log->setUserAgent($message->userAgent);
        $log->setVisitedAt($message->timestamp);

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}
