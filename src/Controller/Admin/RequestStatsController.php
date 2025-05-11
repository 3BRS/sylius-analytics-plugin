<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use ThreeBRS\SyliusAnalyticsPlugin\Repository\RequestLogRepository;

final class RequestStatsController extends AbstractController
{
    public function __construct(private RequestLogRepository $requestLogRepository) {}

    public function __invoke(): Response
    {
        $mostRequestedPages = $this->requestLogRepository->findMostVisitedPagesLast7Days(5);

        return $this->render('@ThreeBRSSyliusAnalyticsPlugin/admin/shared/request_log/request_stats.html.twig', [
            'mostRequestedPages' => $mostRequestedPages,
        ]);
    }
}
