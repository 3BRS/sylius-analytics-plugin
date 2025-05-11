<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Repository;

use Sylius\Component\Resource\Repository\RepositoryInterface;
use ThreeBRS\SyliusAnalyticsPlugin\Entity\RequestLogInterface;

interface RequestLogRepositoryInterface extends RepositoryInterface
{
    /**
     * @return RequestLogInterface[]
     */
    public function findMostVisitedPagesLast7Days(int $limit = 10): array;

    /**
     * @param string $routeName
     */
    public function countShopProductRequests(string $productSlug): int;
}
