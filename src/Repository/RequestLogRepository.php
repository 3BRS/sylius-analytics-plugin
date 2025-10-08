<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class RequestLogRepository extends EntityRepository implements RequestLogRepositoryInterface
{
    /**
     * @return array<int, array{routeName: string, visitCount: int}>
     */
    public function findMostVisitedPages(int $days = 7, int $limit = 10): array
    {
        $startDate = new \DateTimeImmutable(sprintf('-%d days', $days));

        $qb = $this->createQueryBuilder('requestLog')
            ->select('requestLog.routeName, COUNT(requestLog.id) AS visitCount')
            ->where('requestLog.createdAt >= :startDate')
            ->andWhere('requestLog.routeName IS NOT NULL')
            ->groupBy('requestLog.routeName')
            ->orderBy('visitCount', 'DESC')
            ->setMaxResults($limit)
            ->setParameter('startDate', $startDate);

        /** @var array<int, array{routeName: string, visitCount: int}> $result */
        $result = $qb->getQuery()->getArrayResult();

        return $result;
    }

    public function countShopProductRequests(string $productSlug): int
    {
        return (int) $this->createQueryBuilder('requestLog')
            ->select('COUNT(requestLog.id)')
            ->where('requestLog.routeName = :route')
            ->andWhere('requestLog.slug = :slug')
            ->setParameter('route', 'sylius_shop_product_show')
            ->setParameter('slug', mb_strtolower(trim($productSlug)))
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function removeOlderThan(\DateTimeInterface $cutoff): int
    {
        $result = $this->createQueryBuilder('requestLog')
            ->delete()
            ->where('requestLog.createdAt < :cutoff')
            ->setParameter('cutoff', $cutoff)
            ->getQuery()
            ->execute();

        return is_int($result) ? $result : 0;
    }
}
