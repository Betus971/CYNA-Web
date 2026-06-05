<?php

namespace App\Repository;

use App\Entity\ChatbotConversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChatbotConversation>
 */
class ChatbotConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatbotConversation::class);
    }

    /**
     * Supprime les conversations anciennes pour respecter la politique de rétention
     * @param int $days Nombre de jours à conserver (par défaut 90 jours)
     * @return int Nombre de conversations supprimées
     */
    public function deleteOldConversations(int $days = 90): int
    {
        $dateLimit = new \DateTimeImmutable("-{$days} days");
        
        return $this->createQueryBuilder('c')
            ->delete()
            ->where('c.createdAt < :dateLimit')
            ->setParameter('dateLimit', $dateLimit)
            ->getQuery()
            ->execute();
    }

    /**
     * Anonymise les données sensibles des conversations anciennes
     * @param int $days Âge minimum des conversations à anonymiser (par défaut 30 jours)
     * @return int Nombre de conversations anonymisées
     */
    public function anonymizeOldConversations(int $days = 30): int
    {
        $dateLimit = new \DateTimeImmutable("-{$days} days");
        
        return $this->createQueryBuilder('c')
            ->update()
            ->set('c.email', ':null')
            ->set('c.fullName', ':null')
            ->set('c.subject', 'Anonymisé')
            ->where('c.createdAt < :dateLimit')
            ->andWhere('c.email IS NOT NULL OR c.fullName IS NOT NULL')
            ->setParameter('dateLimit', $dateLimit)
            ->setParameter('null', null)
            ->getQuery()
            ->execute();
    }
}
