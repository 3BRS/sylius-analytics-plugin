<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Service;

class RequestLogSlugParser implements RequestLogSlugParserInterface
{
    public function parseSlug(string $url): ?string
    {
        $path = parse_url($url, \PHP_URL_PATH);
        if ($path === false || $path === null) {
            return null;
        }
        $parts = explode('/', trim($path, '/'));

        return mb_strtolower(trim(end($parts)));
    }
}
