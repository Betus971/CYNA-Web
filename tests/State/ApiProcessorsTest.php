<?php

namespace App\Tests\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Address;
use App\Entity\Cart;
use App\Entity\PaymentMethod;
use App\Entity\User;
use App\State\AddressProcessor;
use App\State\CartProcessor;
use App\State\PaymentMethodProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

final class ApiProcessorsTest extends TestCase
{
    public function testAddressProcessorAssignsAuthenticatedUser(): void
    {
        $user = (new User())->setEmail('user@example.test')->setPassword('');
        $address = new Address();
        $persist = $this->passthroughProcessor();

        $processor = new AddressProcessor($persist, $this->securityWithUser($user));
        $result = $processor->process($address, $this->createStub(Operation::class));

        self::assertSame($address, $result);
        self::assertSame($user, $address->getUser());
    }

    public function testAddressProcessorRejectsAnonymousUsers(): void
    {
        $processor = new AddressProcessor($this->passthroughProcessor(), $this->securityWithUser(null));

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Authentification requise.');

        $processor->process(new Address(), $this->createStub(Operation::class));
    }

    public function testCartProcessorKeepsAnonymousCartButAttachesLoggedInUser(): void
    {
        $anonymousCart = new Cart();
        $anonymousProcessor = new CartProcessor($this->passthroughProcessor(), $this->securityWithUser(null));

        $anonymousProcessor->process($anonymousCart, $this->createStub(Operation::class));
        self::assertNull($anonymousCart->getUser());

        $user = (new User())->setEmail('cart@example.test')->setPassword('');
        $cart = new Cart();
        $processor = new CartProcessor($this->passthroughProcessor(), $this->securityWithUser($user));

        $processor->process($cart, $this->createStub(Operation::class));

        self::assertSame($user, $cart->getUser());
    }

    public function testPaymentMethodProcessorAssignsUserAndDefaultProviderMetadata(): void
    {
        $user = (new User())->setEmail('pay@example.test')->setPassword('');
        $method = (new PaymentMethod())
            ->setBrand('visa')
            ->setLast4('4242')
            ->setExpMonth(12)
            ->setExpYear(2030);

        $processor = new PaymentMethodProcessor($this->passthroughProcessor(), $this->securityWithUser($user));
        $result = $processor->process($method, $this->createStub(Operation::class));

        self::assertSame($method, $result);
        self::assertSame($user, $method->getUser());
        self::assertSame('mock', $method->getProvider());
        self::assertStringStartsWith('mock_', (string) $method->getProviderToken());
    }

    public function testPaymentMethodProcessorRejectsAnonymousUsers(): void
    {
        $processor = new PaymentMethodProcessor($this->passthroughProcessor(), $this->securityWithUser(null));

        $this->expectException(AccessDeniedHttpException::class);

        $processor->process(new PaymentMethod(), $this->createStub(Operation::class));
    }

    private function passthroughProcessor(): ProcessorInterface
    {
        return new class implements ProcessorInterface {
            public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
            {
                return $data;
            }
        };
    }

    private function securityWithUser(?User $user): Security
    {
        $security = $this->createStub(Security::class);
        $security->method('getUser')->willReturn($user);

        return $security;
    }
}
