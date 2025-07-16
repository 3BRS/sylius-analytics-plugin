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

        $qb = $this->createQueryBuilder('r')
            ->select('r.routeName, COUNT(r.id) AS visitCount')
            ->where('r.createdAt >= :startDate')
            ->andWhere('r.routeName IS NOT NULL')
            ->groupBy('r.routeName')
            ->orderBy('visitCount', 'DESC')
            ->setMaxResults($limit)
            ->setParameter('startDate', $startDate);

        /** @var array<int, array{routeName: string, visitCount: int}> $result */
        $result = $qb->getQuery()->getArrayResult();

        return $result;
    }

    public function countShopProductRequests(string $productSlug): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.routeName = :route')
            ->andWhere('r.url LIKE :slug')
            ->setParameter('route', 'sylius_shop_product_show')
            ->setParameter('slug', '%/' . $productSlug)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function removeOlderThan(\DateTimeInterface $cutoff): int
    {
        $result = $this->createQueryBuilder('r')
            ->delete()
            ->where('r.createdAt < :cutoff')
            ->setParameter('cutoff', $cutoff)
            ->getQuery()
            ->execute();

        return is_int($result) ? $result : 0;
    }
}
