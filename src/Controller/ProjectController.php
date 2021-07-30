<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\Type\Project\CreateProjectType;
use App\Form\Type\Project\EditProjectType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projects")
 */
class ProjectController extends AbstractController {
    
    /**
     * @Route("/create", name="app_project_create")
     */
    public function create(Request $request): Response
    {
        $project = new Project();
        
        $form = $this->createForm(CreateProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('app_project_show', [
                'slug' => $project->getSlug(),
            ]);
        }

        return $this->render('Page/Project/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="app_project_list")
     */
    public function list(): Response
    {
        $projects = $this->getDoctrine()->getRepository(Project::class)->findAll();

        return $this->render('Page/Project/list.html.twig', [
            'projects' => $projects,
        ]);
    }
    
    /**
     * @Route("/{slug}/edit", name="app_project_edit")
     */
    public function edit(Request $request, Project $project): Response
    {
        $form = $this->createForm(EditProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('app_project_show', [
                'slug' => $project->getSlug(),
            ]);
        }

        return $this->render('Page/Project/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="app_project_show")
     */
    public function show(Project $project): Response
    {
        return $this->render('Page/Project/show.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * @Route("/{slug}/delete", name="app_project_delete")
     */
    public function delete(Project $project): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();

        return $this->redirectToRoute('app_project_list');
    }
}