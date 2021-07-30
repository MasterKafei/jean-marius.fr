<?php

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\Message;


class MessageCreationListener
{
    public function prePersist(Message $message, LifecycleEventArgs $args)
    {
        $message->setCreationDate(new \DateTime());
    }
}