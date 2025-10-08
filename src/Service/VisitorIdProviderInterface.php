<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Service;

use Symfony\Component\HttpFoundation\Request;

interface VisitorIdProviderInterface
{
    public function getVisitorIdFromRequest(Request $request): string;
}
