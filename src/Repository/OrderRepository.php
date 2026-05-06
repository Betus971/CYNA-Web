<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use App\Enum\OrderStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * Historique des commandes d'un utilisateur, groupé par année (exigence CdC).
     *
     * @return array<int, Order[]>  Clé = année, valeur = liste des Order
     */
    public function findByUserGroupedByYear(User $user): array
    {
        /** @var Order[] $orders */
        $orders = $this->createQueryBuilder('o')
            ->andWhere('o.user = :user')
            ->setParameter('user', $user)
            ->orderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        $grouped = [];
        foreach ($orders as $order) {
            $year = (int) $order->getCreatedAt()->format('Y');
            $grouped[$year][] = $order;
        }

        return $grouped;
    }

    /**
     * Stats chiffre d'affaires par jour sur une période (histogramme du dashboard).
     *
     * @return array<int, array{day: string, total: string, count: int}>
     */
    public function salesByDay(\DateTimeImmutable $from, \DateTimeImmutable $to): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = <<<SQL
            SELECT DATE(o.created_at) AS day,
                   SUM(o.total_price)  AS total,
                   COUNT(o.id)         AS count
            FROM `order` o
            WHERE o.created_at BETWEEN :from AND :to
              AND o.status IN (:paid_statuses)
            GROUP BY DATE(o.created_at)
            ORDER BY day ASC
        SQL;

        $stmt = $conn->executeQuery(
            $sql,
            [
                'from' => $from->format('Y-m-d H:i:s'),
                'to' => $to->format('Y-m-d H:i:s'),
                'paid_statuses' => [OrderStatus::PAID->value, OrderStatus::ACTIVE->value, OrderStatus::RENEWED->value],
            ],
            [
                'paid_statuses' => \Doctrine\DBAL\ArrayParameterType::STRING,
            ]
        );

        return $stmt->fetchAllAssociative();
    }

    /**
     * Chiffre d'affaires par catégorie (graphe multi-couches + pie chart du dashboard).
     *
     * @return array<int, array{category: string, total: string, count: int}>
     */
    public function salesByCategory(\DateTimeImmutable $from, \DateTimeImmutable $to): array
    {
        return $this->createQueryBuilder('o')
            ->select('c.name AS category, SUM(oi.unitPriceSnapshot * oi.quantity * oi.durationMonths) AS total, COUNT(oi.id) AS count')
            ->join('o.items', 'oi')
            ->join('oi.saasService', 's')
            ->join('s.category', 'c')
            ->andWhere('o.createdAt BETWEEN :from AND :to')
            ->andWhere('o.status IN (:paid)')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('paid', [OrderStatus::PAID, OrderStatus::ACTIVE, OrderStatus::RENEWED])
            ->groupBy('c.id')
            ->orderBy('total', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Chiffre d'affaires global sur la période.
     */
    public function totalRevenue(\DateTimeImmutable $from, \DateTimeImmutable $to): string
    {
        $result = $this->createQueryBuilder('o')
            ->select('SUM(o.totalPrice)')
            ->andWhere('o.createdAt BETWEEN :from AND :to')
            ->andWhere('o.status IN (:paid)')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('paid', [OrderStatus::PAID, OrderStatus::ACTIVE, OrderStatus::RENEWED])
            ->getQuery()
            ->getSingleScalarResult();

        return (string) ($result ?? '0.00');
    }
}
