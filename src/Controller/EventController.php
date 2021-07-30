<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EventController extends AbstractController
{
    public function create(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(CreateEventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('app_event_list');
        }

        return $this->render('Page/Event/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function edit(Request $request, Event $event)
    {
        $form = $this->createForm(EditEventType::class, $event);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('app_event_list');
        }

        return $this->render('Page/Event/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function delete(Event $event)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('app_event_list');
    }

    public function list()
    {

    }
}