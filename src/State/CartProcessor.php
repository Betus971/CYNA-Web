<?php

namespace App\State;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Cart;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Rattache les paniers API aux utilisateurs connectes.
 *
 * @implements ProcessorInterface<Cart, Cart>
 */
final class CartProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private readonly ProcessorInterface $persistProcessor,
        private readonly Security $security,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Cart
    {
        $user = $this->security->getUser();
        if ($data->getUser() === null && $user instanceof User) {
            $data->setUser($user);
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
