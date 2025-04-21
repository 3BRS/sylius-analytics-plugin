<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Controller;

use Symfony\Component\HttpFoundation\Response;

final class PingController
{
    public function __invoke(): Response
    {
        dump('✅ ThreeBRSSyliusAnalyticsExtension was loaded');

        return new Response('pong from analytics plugin');
    }
}
