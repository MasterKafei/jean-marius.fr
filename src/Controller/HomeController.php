<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home_index")
     */
    public function index(): Response
    {
        $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('Page/Home/index.html.twig');
    }
}
