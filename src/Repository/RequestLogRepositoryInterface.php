<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Repository;

use Sylius\Component\Resource\Repository\RepositoryInterface;

interface RequestLogRepositoryInterface extends RepositoryInterface
{
    /**
     * Returns the most visited routes in the last `$days` days, limited by `$limit`.
     *
     * @return array<int, array{routeName: string, visitCount: int}>
     */
    public function findMostVisitedPages(int $days = 7, int $limit = 10): array;

    public function countShopProductRequests(string $productSlug): int;

    /**
     * Deletes logs older than the given cutoff date.
     *
     * @return int Number of rows deleted.
     */
    public function removeOlderThan(\DateTimeInterface $cutoff): int;
}
