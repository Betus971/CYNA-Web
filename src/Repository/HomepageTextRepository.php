<?php

namespace App\Repository;

use App\Entity\HomepageText;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HomepageText>
 */
class HomepageTextRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HomepageText::class);
    }

    public function findBySlug(string $slug): ?HomepageText
    {
        return $this->findOneBy(['slug' => $slug]);
    }
}
