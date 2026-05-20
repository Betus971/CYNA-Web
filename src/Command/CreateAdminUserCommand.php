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
    name: 'app:create-admin',
    description: 'Crée un utilisateur avec le rôle ROLE_ADMIN',
)]
class CreateAdminUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface      $em,
        private readonly UserPasswordHasherInterface $hasher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email',     InputArgument::REQUIRED, 'Email de l\'administrateur')
            ->addArgument('password',  InputArgument::REQUIRED, 'Mot de passe')
            ->addArgument('firstname', InputArgument::OPTIONAL, 'Prénom', 'Admin')
            ->addArgument('lastname',  InputArgument::OPTIONAL, 'Nom',    'CYNA');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getArgument('email');

        $existing = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existing) {
            // Promote to admin AND set a password (utile pour les comptes SSO sans mot de passe)
            $existing->setRoles(['ROLE_ADMIN']);
            $hashed = $this->hasher->hashPassword($existing, $input->getArgument('password'));
            $existing->setPassword($hashed);
            $this->em->flush();
            $io->success(sprintf('Utilisateur "%s" promu ROLE_ADMIN avec mot de passe défini.', $email));
            $io->note('Tu peux maintenant te connecter sur /admin avec cet email et ce mot de passe.');
            return Command::SUCCESS;
        }

        $user = new User();
        $user->setEmail($email);
        $user->setFirstname($input->getArgument('firstname'));
        $user->setLastname($input->getArgument('lastname'));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setIsVerified(true);

        $hashed = $this->hasher->hashPassword($user, $input->getArgument('password'));
        $user->setPassword($hashed);

        $this->em->persist($user);
        $this->em->flush();

        $io->success(sprintf('Administrateur "%s" créé avec succès.', $email));

        return Command::SUCCESS;
    }
}
