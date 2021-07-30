<?php

namespace App\Command\User;

use App\Business\UserBusiness;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

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
            ->addOption('role', 'r', InputOption::VALUE_OPTIONAL)
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $this->ask($input, $output, 'username');
        $password = $this->ask($input, $output, 'password');
        $firstName = $this->ask($input, $output, 'first_name');
        $lastName = $this->ask($input, $output, 'last_name');
        $roles = [null === $input->getOption('role') ? 'ROLE_USER' : $input->getOption('role')];

        $user = (new User())
            ->setUsername($username)
            ->setPlainPassword($password)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setRoles($roles)
        ;

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    public function ask(InputInterface $input, OutputInterface $output, string $optionName)
    {
        $optionValue = $input->getOption($optionName);

        if(null === $optionValue) {
            $helper = $this->getHelper('question');
            $question = new Question("$optionName:");
            $question->setValidator(function($value) use ($optionName) {
                if(empty(trim($value))) {
                    throw new \Exception("$optionName cannot be empty");
                }

                return $value;
            });
            $question->setHidden(true);
            $question->setMaxAttempts(20);

            $optionValue = $helper->ask($input, $output, $question);
        }

        return $optionValue;
    }
}
