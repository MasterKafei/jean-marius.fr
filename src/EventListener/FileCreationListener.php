<?php

namespace App\EventListener;
use App\Entity\File;
use Doctrine\ORM\Event\LifecycleEventArgs;

class FileCreationListener {
    
    public function prePersist(File $file, LifecycleEventArgs $args)
    {
        $file->setCreationDate(new \DateTime());
    }
}