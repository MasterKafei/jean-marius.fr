<?php

namespace App\Controller;

use App\Form\Type\Authentication\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/authentication")
 */
class AuthenticationController extends AbstractController
{
    /**
     * @Route("/login", name="app_authentication_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if (null !== $this->getUser()) {
            return $this->redirectToRoute('app_home_index');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class);
        $form->get('username')->setData($lastUsername);

        return $this->render('Page/Authentication/login.html.twig', [
            'error' => $error,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="app_authentication_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method should never be called');
    }
}
