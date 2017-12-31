<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ExpenseRepository extends EntityRepository
{
    public function findAllByUser(User $user, int $limit = null, int $offset = null)
    {
        return $this->createLimitedQueryBuilder($limit, $offset)
            ->where('e.user = :user')
            ->orderBy('e.id', 'DESC')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findAllTodayByUser(User $user, int $limit = null, int $offset = null): array
    {
        return $this->createLimitedQueryBuilder($limit, $offset)
            ->where('e.createdAt > :from')
            ->andWhere('e.createdAt < :to')
            ->andWhere('e.user = :user')
            ->setParameter('user', $user)
            ->setParameter('from', (new \DateTime())->format('Y-m-d') . ' 00:00:00')
            ->setParameter('to', (new \DateTime())->format('Y-m-d') . ' 23:59:59')
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findTotalByUser(User $user): float
    {
        return $this->createQueryBuilder('e')
            ->select('SUM(e.amount)')
            ->where('e.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult() ?? 0;
    }

    public function findTotalTodayByUser(User $user): float
    {
        return $this->createQueryBuilder('e')
            ->select('SUM(e.amount)')
            ->where('e.createdAt > :from')
            ->andWhere('e.createdAt < :to')
            ->andWhere('e.user = :user')
            ->setParameter('user', $user)
            ->setParameter('from', (new \DateTime())->format('Y-m-d') . ' 00:00:00')
            ->setParameter('to', (new \DateTime())->format('Y-m-d') . ' 23:59:59')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;
    }

    public function findTotalExpenseCountByUser(User $user): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->where('e.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findTotalTodayExpenseCountByUser(User $user): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->where('e.createdAt > :from')
            ->andWhere('e.createdAt < :to')
            ->andWhere('e.user = :user')
            ->setParameter('user', $user)
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