<?php

namespace App\Controller;

use App\Form\Type\User\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/profile", name="app_user_profile")
     */
    public function profile(Request $request)
    {
        $user = $this->getUser();

        if (null === $user) {
            return $this->redirectToRoute('app_authentication_login');
        }

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        
        return $this->render('Page/User/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}