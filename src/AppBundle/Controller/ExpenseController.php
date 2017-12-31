<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Expense;
use AppBundle\Entity\ExpenseRepository;
use AppBundle\Form\ExpenseFormType;
use AppBundle\Utils\Pagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/expense")
 * @Security("is_granted('ROLE_USER')")
 */
class ExpenseController extends Controller
{
    const PER_PAGE_LIMIT = 5;

    /**
     * @Route("/list/all", name="expense.list.all")
     * @param Request $request
     * @return Response
     */
    public function listAllAction(Request $request): Response
    {
        $user = $this->getUser();
        $pagination = new Pagination(
            $request,
            $this->getExpenseRepo()->findTotalExpenseCountByUser($user),
            self::PER_PAGE_LIMIT
        );
        $expenses = $this->getExpenseRepo()->findAllByUser(
            $user,
            $pagination->getPageLimit(),
            $pagination->getOffset()
        );
        return $this->render(':expense:list.html.twig', [
            'expenses' => $expenses,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/list/today", name="expense.list.today")
     * @param Request $request
     * @return Response
     */
    public function listTodayAction(Request $request): Response
    {
        $user = $this->getUser();
        $pagination = new Pagination(
            $request,
            $this->getExpenseRepo()->findTotalTodayExpenseCountByUser($user),
            self::PER_PAGE_LIMIT
        );
        $expenses = $this->getExpenseRepo()->findAllTodayByUser(
            $user,
            $pagination->getPageLimit(),
            $pagination->getOffset()
        );
        return $this->render(':expense:list.html.twig', [
            'expenses' => $expenses,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="expense.new")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(ExpenseFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Expense $expense */
            $expense = $form->getData();
            $expense->setUser($this->getUser());
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