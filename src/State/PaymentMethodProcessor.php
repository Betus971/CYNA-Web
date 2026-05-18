<?php

namespace App\State;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\PaymentMethod;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Sur POST /api/payment_methods : on assigne le user et on génère un providerToken mock.
 * En production, ce token devrait venir d'un appel Stripe SetupIntent côté front.
 *
 * @implements ProcessorInterface<PaymentMethod, PaymentMethod>
 */
final class PaymentMethodProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private readonly ProcessorInterface $persistProcessor,
        private readonly Security $security,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): PaymentMethod
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw new AccessDeniedHttpException('Authentification requise.');
        }

        if ($data->getUser() === null) {
            $data->setUser($user);
        }

        // En attendant l'intégration Stripe, on génère un identifiant mock.
        if (empty($data->getProviderToken())) {
            $data->setProviderToken('mock_' . bin2hex(random_bytes(8)));
        }

        if (empty($data->getProvider())) {
            $data->setProvider('mock');
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
