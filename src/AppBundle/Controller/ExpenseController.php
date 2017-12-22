<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Expense;
use AppBundle\Entity\ExpenseRepository;
use AppBundle\Utils\Pagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpenseController extends Controller
{
    const PER_PAGE_LIMIT = 5;

    /**
     * @Route("/", name="home")
     */
    public function indexAction(): Response
    {
        return $this->redirectToRoute('expense.list.today');
    }

    /**
     * @Route("/expense/list/all", name="expense.list.all")
     * @param Request $request
     * @return Response
     */
    public function listAllAction(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = new Pagination($this->getExpenseRepo()->findTotalExpenseCount(), self::PER_PAGE_LIMIT, $page);
        $expenses = $this->getExpenseRepo()->findAll($pagination->getPageLimit(), $pagination->getOffset());

        return $this->renderExpensesView($expenses, [
            'page' => $pagination->getPage(),
            'total_pages' => $pagination->getTotalPages(),
        ]);
    }

    /**
     * @Route("/expense/list/today", name="expense.list.today")
     */
    public function listTodayAction(): Response
    {
        $expenses = $this->getExpenseRepo()->findAllToday();
        return $this->renderExpensesView($expenses);
    }

    /**
     * @param array $expenses
     * @param array $params
     * @return Response
     */
    private function renderExpensesView(array $expenses, array $params = []): Response
    {
        return $this->render(':expense:list.html.twig', [
            'expenses' => $expenses,
            'total' => $this->getExpenseRepo()->findTotal(),
            'today' => $this->getExpenseRepo()->findTotalToday(),
        ] + $params);
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

    private function getExpenseRepo(): ExpenseRepository
    {
        return $this->getDoctrine()->getManager()->getRepository(Expense::class);
    }
}