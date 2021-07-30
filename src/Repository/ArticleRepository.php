<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class ArticleRepository extends EntityRepository
{
    public function findWrittenByConnectedUser()
    {
        $query = $this->createQueryBuilder('article');

        $query
            ->where(
                $query->expr()->isNotNull('article.author')
            )
        ;

        return $query->getQuery()->getResult();
    }

    public function findAllWrittenByCurrentUser($clientIp)
    {
        $query = $this->createQueryBuilder('article');

        $query
            ->where(
                $query->expr()->isNull('article.author')
            )
            ->andWhere(
                $query->expr()->eq('article.ip', ':ip')
            )
        ;

        $query->setParameter('ip', $clientIp);

        return $query->getQuery()->getResult();
    }
}