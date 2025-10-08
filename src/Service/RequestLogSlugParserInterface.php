<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Service;

interface RequestLogSlugParserInterface
{
    public function parseSlug(string $url): ?string;
}
