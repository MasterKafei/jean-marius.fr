<?php

namespace App\EventListener;

use App\Entity\Article;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\RequestStack;

class ArticleCreationListener
{
    private RequestStack $requestStack;

    public function setRequest(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        /** @var Article $article */
        $article = $args->getEntity();
        $article
            ->setCreationDate(new \DateTime())
            ->setIp($this->requestStack->getMainRequest()->getClientIp())
        ;
    }
}
