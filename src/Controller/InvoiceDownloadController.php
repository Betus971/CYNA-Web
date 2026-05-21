<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Service\InvoicePdfService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class InvoiceDownloadController extends AbstractController
{
    public function __construct(
        private readonly InvoicePdfService $pdfService,
    ) {
    }

    /**
     * Télécharge la facture PDF d'une commande.
     * Accessible par le propriétaire de la commande ou un admin.
     */
    #[Route('/api/invoices/{id}/download', name: 'invoice_download', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function __invoke(Invoice $invoice): Response
    {
        $order = $invoice->getOrder();
        $user  = $this->getUser();

        // Vérification d'accès : propriétaire ou admin
        if (!$this->isGranted('ROLE_ADMIN') && ($order === null || $order->getUser() !== $user)) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas accéder à cette facture.');
        }

        $path = $this->pdfService->generate($invoice);

        $response = new BinaryFileResponse($path);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            sprintf('facture-%s.pdf', $invoice->getNumber()),
        );
        $response->headers->set('Content-Type', 'application/pdf');

        return $response;
    }
}
