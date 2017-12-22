<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ExpenseRepository extends EntityRepository
{
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
}