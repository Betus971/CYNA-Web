<?php

namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Endpoints du backoffice (ROLE_ADMIN uniquement) — alimentent le dashboard :
 *  - KPI (CA global, nombre de commandes, nombre d'utilisateurs),
 *  - histogramme CA/jour,
 *  - multi-couches CA/catégorie/jour,
 *  - pie chart CA/catégorie.
 */
#[Route('/api/admin/dashboard', name: 'admin_dashboard_')]
#[IsGranted('ROLE_ADMIN')]
final class DashboardController extends AbstractController
{
    public function __construct(
        private readonly OrderRepository $orders,
        private readonly UserRepository $users,
    ) {
    }

    #[Route('/kpi', name: 'kpi', methods: ['GET'])]
    public function kpi(Request $request): JsonResponse
    {
        [$from, $to] = $this->extractRange($request);

        return $this->json([
            'range' => ['from' => $from->format(DATE_ATOM), 'to' => $to->format(DATE_ATOM)],
            'revenue' => $this->orders->totalRevenue($from, $to),
            'newUsers' => $this->users->countCreatedBetween($from, $to),
        ]);
    }

    #[Route('/sales-by-day', name: 'sales_by_day', methods: ['GET'])]
    public function salesByDay(Request $request): JsonResponse
    {
        [$from, $to] = $this->extractRange($request);

        return $this->json($this->orders->salesByDay($from, $to));
    }

    #[Route('/sales-by-category', name: 'sales_by_category', methods: ['GET'])]
    public function salesByCategory(Request $request): JsonResponse
    {
        [$from, $to] = $this->extractRange($request);

        return $this->json($this->orders->salesByCategory($from, $to));
    }

    /**
     * @return array{0: \DateTimeImmutable, 1: \DateTimeImmutable}
     */
    private function extractRange(Request $request): array
    {
        $fromParam = (string) $request->query->get('from', '');
        $toParam = (string) $request->query->get('to', '');

        try {
            $from = '' !== $fromParam
                ? new \DateTimeImmutable($fromParam)
                : new \DateTimeImmutable('-30 days');
            $to = '' !== $toParam
                ? new \DateTimeImmutable($toParam)
                : new \DateTimeImmutable();
        } catch (\Exception) {
            $from = new \DateTimeImmutable('-30 days');
            $to = new \DateTimeImmutable();
        }

        return [$from, $to];
    }
}
