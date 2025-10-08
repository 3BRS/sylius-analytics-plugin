<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Message;

class LogVisitMessage
{
    public function __construct(
        public string $url,
        public string $route,
        public string $channel,
        public ?string $slug,
        public ?string $customer,
        public ?string $visitorId,
        public ?string $ip,
        public ?string $userAgent,
    ) {
    }
}
