<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Expense;
use AppBundle\Entity\ExpenseRepository;
use Doctrine\ORM\EntityManager;

class ExpenseExtension extends \Twig_Extension
{
    /** @var ExpenseRepository */
    private $expenseRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->expenseRepository = $entityManager->getRepository(Expense::class);
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'total_expense',
                [$this, 'totalExpense']
            ),
            new \Twig_SimpleFunction(
                'today_total_expense',
                [$this, 'todayTotalExpense']
            )
        ];
    }

    public function totalExpense(): float
    {
        return $this->expenseRepository->findTotal();
    }

    public function todayTotalExpense(): float
    {
        return $this->expenseRepository->findTotalToday();
    }
}