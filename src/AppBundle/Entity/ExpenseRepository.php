<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ExpenseRepository extends EntityRepository
{
    public function findAll(int $limit = null, int $offset = null)
    {
        $query = $this->createQueryBuilder('e');
        if ($offset) {
            $query->setFirstResult($offset);
        }
        if ($limit) {
            $query->setMaxResults($limit);
        }
        return $query
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Expense[]
     */
    public function findAllToday(): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.createdAt > :from')
            ->andWhere('e.createdAt < :to')
            ->setParameter('from', (new \DateTime())->format('Y-m-d') . ' 00:00:00')
            ->setParameter('to', (new \DateTime())->format('Y-m-d') . ' 23:59:59')
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
}