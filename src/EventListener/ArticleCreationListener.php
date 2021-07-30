<?php

namespace App\EventListener;

use App\Entity\Article;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\RequestStack;

class ArticleCreationListener
{
    private RequestStack $requestStack;

    /** @required */
    public function setRequest(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function prePersist(Article $article, LifecycleEventArgs $args)
    {
        $article
            ->setCreationDate(new \DateTime())
            ->setIp($this->requestStack->getMainRequest()->getClientIp())
        ;
    }
}
