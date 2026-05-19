<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GoogleController extends AbstractController
{
    #[Route('/login/google', name: 'login_google')]
    public function redirectToGoogle(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect(['email', 'profile'], []);
    }

    /**
     * Route interceptée par GoogleAuthenticator — le corps n'est jamais exécuté.
     */
    #[Route('/login/google/check', name: 'login_google_check')]
    public function check(): Response
    {
        throw new \LogicException('This route is handled by GoogleAuthenticator.');
    }
}
