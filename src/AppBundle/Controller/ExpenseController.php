<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Expense;
use AppBundle\Entity\ExpenseRepository;
use AppBundle\Form\ExpenseFormType;
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
        $pagination = new Pagination($request, $this->getExpenseRepo()->findTotalExpenseCount(), self::PER_PAGE_LIMIT);
        $expenses = $this->getExpenseRepo()->findAll($pagination->getPageLimit(), $pagination->getOffset());
        return $this->render(':expense:list.html.twig', [
            'expenses' => $expenses,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/expense/list/today", name="expense.list.today")
     * @param Request $request
     * @return Response
     */
    public function listTodayAction(Request $request): Response
    {
        $pagination = new Pagination(
            $request,
            $this->getExpenseRepo()->findTotalTodayExpenseCount(),
            self::PER_PAGE_LIMIT
        );
        $expenses = $this->getExpenseRepo()->findAllToday($pagination->getPageLimit(), $pagination->getOffset());
        return $this->render(':expense:list.html.twig', [
            'expenses' => $expenses,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/expense/new", name="expense.new")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(ExpenseFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $expense = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($expense);
            $em->flush();

            $this->addFlash('success', 'Expense created');

            return $this->redirectToRoute('expense.list.today');
        }

        return $this->render(':expense:new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getExpenseRepo(): ExpenseRepository
    {
        return $this->getDoctrine()->getManager()->getRepository(Expense::class);
    }
}