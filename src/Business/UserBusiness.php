<?php

namespace App\Business;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserBusiness
{
    private UserPasswordHasherInterface $userPasswordHasher;

    /** @required */
    public function setPasswordHasher(UserPasswordHasherInterface $userPasswordHasher): self
    {
        $this->userPasswordHasher = $userPasswordHasher;

        return $this;
    }

    public function hashPassword(User $user)
    {
        if (null === $user->getPlainPassword()) {
            throw new \Exception('Plain password is not initialize');
        }

        $password = $this->userPasswordHasher->hashPassword($user, $user->getPlainPassword());

        $user->setPassword($password);
    }
}
