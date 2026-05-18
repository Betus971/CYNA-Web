<?php

namespace App\State;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Address;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Sur POST /api/addresses : on assigne automatiquement l'adresse à l'utilisateur connecté.
 *
 * @implements ProcessorInterface<Address, Address>
 */
final class AddressProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private readonly ProcessorInterface $persistProcessor,
        private readonly Security $security,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Address
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw new AccessDeniedHttpException('Authentification requise.');
        }

        if ($data->getUser() === null) {
            $data->setUser($user);
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
