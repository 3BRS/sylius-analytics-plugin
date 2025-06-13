<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Repository;

use Sylius\Component\Resource\Repository\RepositoryInterface;

interface RequestLogRepositoryInterface extends RepositoryInterface
{
    /**
     * @return array<int, array{routeName: string, visitCount: int}>
     */
    public function findMostVisitedPagesLast7Days(int $limit = 10): array;

    public function countShopProductRequests(string $productSlug): int;
}
