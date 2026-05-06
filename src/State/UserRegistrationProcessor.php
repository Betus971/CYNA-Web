<?php

namespace App\State;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use App\Service\EmailVerifier;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Processor exécuté lors d'un POST /api/users (inscription).
 *  - Hache le mot de passe clair reçu via plainPassword.
 *  - Force ROLE_USER (évite une élévation de privilège par le payload).
 *  - Force isVerified = false.
 *  - Génère un token de vérification d'email et déclenche l'envoi du mail.
 *
 * @implements ProcessorInterface<User, User>
 */
final class UserRegistrationProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private readonly ProcessorInterface $persistProcessor,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EmailVerifier $emailVerifier,
    ) {
    }

    /**
     * @param User $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User
    {
        if (null !== $data->getPlainPassword()) {
            $data->setPassword(
                $this->passwordHasher->hashPassword($data, $data->getPlainPassword())
            );
            $data->setPlainPassword(null);
        }

        // Par sécurité : on ne laisse jamais le payload choisir les rôles.
        $data->setRoles(['ROLE_USER']);
        $data->setIsVerified(false);
        $data->setEmailVerificationToken(bin2hex(random_bytes(32)));
        $data->setEmailVerificationSentAt(new \DateTimeImmutable());

        /** @var User $persisted */
        $persisted = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        $this->emailVerifier->sendVerificationEmail($persisted);

        return $persisted;
    }
}
