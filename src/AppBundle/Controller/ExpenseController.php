<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Expense;
use Faker\Provider\DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ExpenseController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(): Response
    {
        return $this->redirectToRoute('expense.list');
    }

    /**
     * @Route("/expense/list", name="expense.list")
     */
    public function listAction(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $expenses = $em->getRepository(Expense::class)->findAll();

        return $this->render(':expense:list.html.twig', [
            'expenses' => $expenses,
            'total' => $this->getTotalSpent($expenses),
            'today' => $this->getTodaySpent($expenses),
        ]);
    }

    /**
     * @Route("/expense/new", name="expense.new")
     */
    public function newAction()
    {
        $expense = new Expense();
        $expense->setAmount(rand(0, 100));

        $em = $this->getDoctrine()->getManager();
        $em->persist($expense);
        $em->flush();

        return new Response('<html><body>Expense created</body></html>');
    }

    /**
     * @param Expense[] $expenses
     * @return float
     */
    private function getTotalSpent(array $expenses): float
    {
        return array_sum(array_map(function(Expense $expense) {
            return $expense->getAmount();
        }, $expenses));
    }

    /**
     * @param Expense[] $expenses
     * @return float
     */
    private function getTodaySpent(array $expenses): float
    {
        $todayExpenses = array_filter($expenses, function(Expense $expense) {
            return $expense->getCreatedAt()->format('Y-m-d') === (new \DateTime('now'))->format('Y-m-d');
        });
        return array_sum(array_map(function(Expense $expense) {
            return $expense->getAmount();
        }, $todayExpenses));
    }
}