<?php

namespace App\Command\User;

use App\Business\UserBusiness;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create:user';

    private UserBusiness $userBusiness;

    private EntityManagerInterface $entityManager;

    /** @required */
    public function setUserBusiness(UserBusiness $userBusiness): self
    {
        $this->userBusiness = $userBusiness;

        return $this;
    }

    /** @required */
    public function setEntityManager(EntityManagerInterface $entityManager): self
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    public function configure()
    {
        $this
            ->addOption('username', 'u', InputOption::VALUE_OPTIONAL)
            ->addOption('password', 'p', InputOption::VALUE_OPTIONAL)
            ->addOption('first_name', 'f', InputOption::VALUE_OPTIONAL)
            ->addOption('last_name', 'l', InputOption::VALUE_OPTIONAL)
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = (new User())
            ->setUsername($input->getOption('username'))
            ->setPlainPassword($input->getOption('password'))
            ->setFirstName($input->getOption('first_name'))
            ->setLastName($input->getOption('last_name'))
        ;

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
