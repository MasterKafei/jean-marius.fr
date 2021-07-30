<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\Type\Message\CreateMessageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contact")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/", name="app_contact_request")
     */
    public function request(Request $request): Response
    {
        $message = new Message();

        if ($user = $this->getUser()) {
            $message
                ->setFirstName($user->getFirstName())
                ->setLastName($user->getLastName())
            ;
        }

        $form = $this->createForm(CreateMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('app_home_index');
        }

        return $this->render('Page/Contact/request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/list", name="app_contact_list")
     */
    public function list(): Response
    {
        $messages = $this->getDoctrine()->getRepository(Message::class)->findAll();

        return $this->render('Page/Contact/list.html.twig', [
            'messages' => $messages,
        ]);
    }

    /**
     * @Route("/show/{id}", name="app_contact_show")
     */
    public function show(Message $message): Response
    {
        if (null === $message->getViewedDate()) {
            $message->setViewedDate(new \DateTime());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
        }

        return $this->render('Page/Contact/show.html.twig', [
            'message' => $message,
        ]);
    }
}