<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class RequestLogRepository extends EntityRepository implements RequestLogRepositoryInterface
{
    public function findMostVisitedPagesLast7Days(int $limit = 10): array
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r.routeName, COUNT(r.id) AS visitCount')
            ->where('r.createdAt >= :startDate')
            ->andWhere('r.routeName IS NOT NULL')
            ->groupBy('r.routeName')
            ->orderBy('visitCount', 'DESC')
            ->setMaxResults($limit)
            ->setParameter('startDate', new \DateTimeImmutable('-7 days'));

        return $qb->getQuery()->getResult();
    }

    public function countVisitsByRoute(string $routeName): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.routeName = :routeName')
            ->setParameter('routeName', $routeName)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
