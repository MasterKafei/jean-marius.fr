<?php

namespace App\EventListener;


use App\Business\TokenBusiness;
use App\Business\UserBusiness;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UserCreationListener
{
    private UserBusiness $userBusiness;

    private TokenBusiness $tokenBusiness;

    /** @required */
    public function setUserBusiness(UserBusiness $userBusiness): self
    {
        $this->userBusiness = $userBusiness;

        return $this;
    }

    /** @required */
    public function setTokenBusiness(TokenBusiness $tokenBusiness): self
    {
        $this->tokenBusiness = $tokenBusiness;

        return $this;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        /** @var User $user */
        $user = $args->getEntity();
        $user->setSalt($this->tokenBusiness->generateRandomToken());
        $this->userBusiness->hashPassword($user);
    }
}
