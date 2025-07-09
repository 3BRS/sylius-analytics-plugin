<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\MessageHandler;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Symfony\Component\Clock\ClockInterface;
use ThreeBRS\SyliusAnalyticsPlugin\Entity\RequestLog;
use ThreeBRS\SyliusAnalyticsPlugin\Message\LogVisitMessage;

class LogVisitMessageHandler
{
    /**
     * @param ChannelRepositoryInterface<ChannelInterface> $channelRepository
     * @param CustomerRepositoryInterface<CustomerInterface> $customerRepository
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ChannelRepositoryInterface $channelRepository,
        private CustomerRepositoryInterface $customerRepository,
        private ClockInterface $clock,
    ) {
    }

    public function __invoke(LogVisitMessage $message): void
    {
        $channel = $this->channelRepository->findOneByCode($message->channel);

        if ($channel === null) {
            throw new \RuntimeException(sprintf('Channel with code "%s" not found.', $message->channel));
        }

        $customer = $message->customer !== null
                    ? $this->customerRepository->find($message->customer)
                    : null;

        $log = new RequestLog();
        $log->setUrl($message->url);
        $log->setRouteName($message->route);
        $log->setChannel($channel);
        $log->setCustomer($customer); // now safe
        $log->setSessionId($message->sessionId);
        $log->setIpAddress($message->ip);
        $log->setUserAgent($message->userAgent);
        $log->setCreatedAt($this->clock->now());

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}
