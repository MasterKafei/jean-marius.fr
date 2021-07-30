<?php

namespace App\Controller;

use App\Business\FileBusiness;
use App\Entity\Article;
use App\Form\Type\Article\CreateArticleType;
use App\Form\Type\Article\EditArticleType;
use App\Form\Type\Article\ShowArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/articles")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/create", name="app_article_create")
     */
    public function create(Request $request, FileBusiness $fileBusiness): Response
    {
        $article = new Article();

        $form = $this->createForm(CreateArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            
            $file = $fileBusiness->uploadFile($form->get('preview')->getData(), false);
            $article->setPreview($file);
            $em->flush();
            
            return $this->redirectToRoute('app_article_show', ['slug' => $article->getSlug()]);
        }

        return $this->render('Page/Article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="app_article_edit")
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(EditArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('app_article_show', ['slug' => $article->getSlug()]);
        }

        return $this->render('Page/Article/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}/delete", name="app_article_delete")
     */
    public function delete(Article $article): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('app_article_list');
    }

    /**
     * @Route("/{slug}/show", name="app_article_show")
     */
    public function show(Article $article): Response
    {
        $form = $this->createForm(ShowArticleType::class, $article);

        return $this->render('Page/Article/show.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    /**
     * @Route("/", name="app_article_list")
     */
    public function list(Request $request): Response
    {
        $connectedUserArticles = $this->getDoctrine()->getRepository(Article::class)->findWrittenByConnectedUser();
        $currentUserArticles = $this->getDoctrine()->getRepository(Article::class)
            ->findAllWrittenByCurrentUser($request->getClientIp())
        ;

        $articles = array_merge($connectedUserArticles, $currentUserArticles);

        return $this->render('Page/Article/list.html.twig', [
            'articles' => $articles,
        ]);
    }
}
