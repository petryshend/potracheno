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
        return $this->render(':expense:index.html.twig');
    }

    /**
     * @Route("/expense/list", name="expense.list")
     */
    public function listAction(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $expenses = $em->getRepository(Expense::class)->findAll();

        return $this->render(':expense:list.html.twig', ['expenses' => $expenses]);
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