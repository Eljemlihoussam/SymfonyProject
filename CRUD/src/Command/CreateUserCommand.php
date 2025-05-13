<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Crée un nouvel utilisateur',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Nom d\'utilisateur')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe')
            ->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addArgument('first_name', InputArgument::REQUIRED, 'Prénom')
            ->addArgument('last_name', InputArgument::REQUIRED, 'Nom')
            ->addArgument('role', InputArgument::OPTIONAL, 'Rôle (ROLE_USER par défaut)', 'ROLE_USER');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $email = $input->getArgument('email');
        $firstName = $input->getArgument('first_name');
        $lastName = $input->getArgument('last_name');
        $role = $input->getArgument('role');

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setRoles([$role]);

        // Hash du mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf('Utilisateur "%s" créé avec succès !', $username));

        return Command::SUCCESS;
    }
} 