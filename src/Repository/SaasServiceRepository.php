<?php

namespace App\Repository;

use App\Entity\SaasService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SaasService>
 */
class SaasServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SaasService::class);
    }

    /**
     * @return SaasService[]
     */
    public function findTopByPriority(int $limit = 3): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.isAvailable = true')
            ->andWhere('s.priority IS NOT NULL')
            ->orderBy('s.priority', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return SaasService[]
     */
    public function findByCategoryId(int $categoryId): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.category = :cat')
            ->andWhere('s.isAvailable = true')
            ->setParameter('cat', $categoryId)
            ->orderBy('s.priority', 'ASC')
            ->addOrderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche par facettes : titre/description/catégorie/plage de prix/disponibilité.
     * Correspond aux exigences du CdC (page catalogue avec filtres).
     *
     * @param array{
     *     q?: string|null,
     *     categoryId?: int|null,
     *     minPrice?: float|null,
     *     maxPrice?: float|null,
     *     availableOnly?: bool,
     *     sort?: string,
     *     direction?: string,
     *     limit?: int|null,
     *     offset?: int|null,
     * } $criteria
     *
     * @return SaasService[]
     */
    public function searchFacets(array $criteria = []): array
    {
        $qb = $this->buildSearchQuery($criteria);

        if (!empty($criteria['limit'])) {
            $qb->setMaxResults((int) $criteria['limit']);
        }
        if (!empty($criteria['offset'])) {
            $qb->setFirstResult((int) $criteria['offset']);
        }

        return $qb->getQuery()->getResult();
    }

    public function countFacets(array $criteria = []): int
    {
        $qb = $this->buildSearchQuery($criteria)
            ->select('COUNT(s.id)')
            ->resetDQLPart('orderBy');

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    private function buildSearchQuery(array $criteria): QueryBuilder
    {
        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.category', 'c')
            ->addSelect('c');

        if (!empty($criteria['q'])) {
            $qb->andWhere('s.name LIKE :q OR s.description LIKE :q')
                ->setParameter('q', '%'.$criteria['q'].'%');
        }
        if (!empty($criteria['categoryId'])) {
            $qb->andWhere('s.category = :cat')
                ->setParameter('cat', (int) $criteria['categoryId']);
        }
        if (isset($criteria['minPrice']) && '' !== $criteria['minPrice']) {
            $qb->andWhere('s.price >= :minPrice')
                ->setParameter('minPrice', $criteria['minPrice']);
        }
        if (isset($criteria['maxPrice']) && '' !== $criteria['maxPrice']) {
            $qb->andWhere('s.price <= :maxPrice')
                ->setParameter('maxPrice', $criteria['maxPrice']);
        }
        if (!empty($criteria['availableOnly'])) {
            $qb->andWhere('s.isAvailable = true');
        }

        $allowedSort = ['name' => 's.name', 'price' => 's.price', 'priority' => 's.priority'];
        $sortKey = $criteria['sort'] ?? 'priority';
        $direction = (($criteria['direction'] ?? 'asc') === 'desc') ? 'DESC' : 'ASC';
        $qb->orderBy($allowedSort[$sortKey] ?? 's.priority', $direction)
            ->addOrderBy('s.name', 'ASC');

        return $qb;
    }
}
