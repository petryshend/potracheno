<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Expense;
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
        return $this->redirectToRoute('expense.list.today');
    }

    /**
     * @Route("/expense/list/all", name="expense.list.all")
     */
    public function listAllAction(): Response
    {
        $expenses = $this->getDoctrine()->getManager()->getRepository(Expense::class)->findAll();
        return $this->renderExpensesView($expenses);
    }

    /**
     * @Route("/expense/list/today", name="expense.list.today")
     */
    public function listTodayAction(): Response
    {
        $expenses = $this->getDoctrine()->getManager()->getRepository(Expense::class)->findAllToday();
        return $this->renderExpensesView($expenses);
    }

    /**
     * @param array $expenses
     * @return Response
     */
    private function renderExpensesView(array $expenses): Response
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Expense::class);
        return $this->render(':expense:list.html.twig', [
            'expenses' => $expenses,
            'total' => $repo->findTotal(),
            'today' => $repo->findTotalToday(),
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
}