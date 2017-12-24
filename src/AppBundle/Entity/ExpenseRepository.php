<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ExpenseRepository extends EntityRepository
{
    public function findAll(int $limit = null, int $offset = null)
    {
        return $this->createLimitedQueryBuilder($limit, $offset)
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findAllToday(int $limit = null, int $offset = null): array
    {
        return $this->createLimitedQueryBuilder($limit, $offset)
            ->where('e.createdAt > :from')
            ->andWhere('e.createdAt < :to')
            ->setParameter('from', (new \DateTime())->format('Y-m-d') . ' 00:00:00')
            ->setParameter('to', (new \DateTime())->format('Y-m-d') . ' 23:59:59')
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findTotal(): float
    {
        return $this->createQueryBuilder('e')
            ->select('SUM(e.amount)')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;
    }

    public function findTotalToday(): float
    {
        return $this->createQueryBuilder('e')
            ->select('SUM(e.amount)')
            ->where('e.createdAt > :from')
            ->andWhere('e.createdAt < :to')
            ->setParameter('from', (new \DateTime())->format('Y-m-d') . ' 00:00:00')
            ->setParameter('to', (new \DateTime())->format('Y-m-d') . ' 23:59:59')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;
    }

    public function findTotalExpenseCount(): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findTotalTodayExpenseCount(): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->where('e.createdAt > :from')
            ->andWhere('e.createdAt < :to')
            ->setParameter('from', (new \DateTime())->format('Y-m-d') . ' 00:00:00')
            ->setParameter('to', (new \DateTime())->format('Y-m-d') . ' 23:59:59')
            ->getQuery()
            ->getSingleScalarResult();
    }

    private function createLimitedQueryBuilder(int $limit = null, int $offset = null): QueryBuilder
    {
        $query = $this->createQueryBuilder('e');
        if ($offset) {
            $query->setFirstResult($offset);
        }
        if ($limit) {
            $query->setMaxResults($limit);
        }
        return $query;
    }


}