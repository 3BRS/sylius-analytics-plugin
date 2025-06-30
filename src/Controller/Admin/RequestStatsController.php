<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use ThreeBRS\SyliusAnalyticsPlugin\Repository\RequestLogRepository;
use Twig\Environment;

final class RequestStatsController
{
    public function __construct(
        private RequestLogRepository $requestLogRepository,
        private Environment $twig,
        private int $requestLogDays,
    ) {
    }

    public function __invoke(): Response
    {
        $mostRequestedPages = $this->requestLogRepository->findMostVisitedPages($this->requestLogDays, 5);

        return new Response($this->twig->render(
            '@ThreeBRSSyliusAnalyticsPlugin/admin/shared/request_log/request_stats.html.twig',
            [
                'mostRequestedPages' => $mostRequestedPages,
                'requestLogDays' => $this->requestLogDays,
            ],
        ));
    }
}
