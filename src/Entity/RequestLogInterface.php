<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Entity;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Customer\Model\CustomerInterface;

interface RequestLogInterface
{
    public function getId(): ?int;

    public function getUrl(): string;

    public function setUrl(string $url): void;

    public function getRouteName(): ?string;

    public function setRouteName(?string $routeName): void;

    public function getChannel(): ChannelInterface;

    public function setChannel(ChannelInterface $channel): void;

    public function getCustomer(): ?CustomerInterface;

    public function setCustomer(?CustomerInterface $customer): void;

    public function getSessionId(): ?string;

    public function setSessionId(?string $sessionId): void;

    public function getIpAddress(): ?string;

    public function setIpAddress(?string $ipAddress): void;

    public function getUserAgent(): ?string;

    public function setUserAgent(?string $userAgent): void;

    public function getCreatedAt(): \DateTimeInterface;

    public function setCreatedAt(\DateTimeInterface $createdAt): void;
}
