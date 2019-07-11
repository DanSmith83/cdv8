<?php

// src/Command/CreateUserCommand.php
namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:create-user';

    private $container;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }


    protected function configure()
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'The name of the user.');
        $this->addArgument('email', InputArgument::OPTIONAL, 'The email of the user.');
        $this->addArgument('password', InputArgument::OPTIONAL, 'The password of the user.');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        if (! $input->getArgument('name')) {
            $name = $helper->ask($input, $output, new Question('Name:'));
        } else {
            $name = $input->getArgument('name');
        }

        if (! $input->getArgument('email')) {
            $email = $helper->ask($input, $output, new Question('Email address:'));
        } else {
            $email = $input->getArgument('email');
        }

        if (! $input->getArgument('password')) {
            $password = $helper->ask($input, $output, new Question('Password:'));
        } else {
            $password = $input->getArgument('password');
        }

        $user = new \App\Entity\User;
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $password
            )
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();


        //$output->writeln('You have just selected: '.$bundleName);

        // ...
    }
}