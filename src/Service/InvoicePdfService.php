<?php

namespace App\Service;

use App\Entity\Invoice;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Environment;

/**
 * Génère un PDF de facture via DomPDF et le stocke sur disque.
 */
final class InvoicePdfService
{
    public function __construct(
        private readonly Environment $twig,
        private readonly string $invoiceDir,
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
    ) {
    }

    /**
     * Génère (ou recharge) le PDF de la facture et retourne son chemin absolu.
     */
    public function generate(Invoice $invoice): string
    {
        $filename = sprintf('%s.pdf', $invoice->getNumber());
        $path     = rtrim($this->invoiceDir, '/') . '/' . $filename;

        // On régénère si le fichier n'existe pas encore
        if (!file_exists($path)) {
            $html = $this->twig->render('invoices/invoice_pdf.html.twig', [
                'invoice'     => $invoice,
                'order'       => $invoice->getOrder(),
                'logoDataUri' => $this->getLogoDataUri(),
            ]);

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', false);
            $options->set('defaultFont', 'Arial');

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html, 'UTF-8');
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            if (!is_dir($this->invoiceDir)) {
                mkdir($this->invoiceDir, 0755, true);
            }
            file_put_contents($path, $dompdf->output());
        }

        return $path;
    }

    public function getPublicPath(Invoice $invoice): string
    {
        return sprintf('/invoices/%s.pdf', $invoice->getNumber());
    }

    private function getLogoDataUri(): ?string
    {
        $path = $this->projectDir . '/public/cyna-logo-icon.png';
        if (!is_file($path)) {
            return null;
        }

        $content = file_get_contents($path);
        if ($content === false) {
            return null;
        }

        return 'data:image/png;base64,' . base64_encode($content);
    }
}
