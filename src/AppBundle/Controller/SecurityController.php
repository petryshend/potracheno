<?php

namespace AppBundle\Controller;

use AppBundle\Form\LoginForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security_login")
     * @param Request $request
     * @param AuthenticationUtils $authUtils
     * @return Response
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils): Response
    {
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername,
        ]);

        return $this->render('security/login.html.twig', array(
            'form' => $form->createView(),
            'error' => $error,
        ));
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('This should not be reached');
    }
}