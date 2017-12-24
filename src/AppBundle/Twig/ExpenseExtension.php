<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Expense;
use AppBundle\Entity\ExpenseRepository;
use Doctrine\ORM\EntityManager;

class ExpenseExtension extends \Twig_Extension
{
    const PAGE_TITLE_MAP = [
        'expense.new' => 'Create new expense',
        'expense.list.all' => 'All expenses',
        'expense.list.today' => 'Today expenses',
    ];

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
            ),
            new \Twig_SimpleFunction(
                'page_title',
                [$this, 'getPageTitleForRoute']
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

    public function getPageTitleForRoute(string $routeName): string
    {
        return self::PAGE_TITLE_MAP[$routeName] ?? 'Potracheno';
    }
}