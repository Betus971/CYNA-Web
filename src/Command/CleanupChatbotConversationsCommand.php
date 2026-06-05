<?php

namespace App\Command;

use App\Repository\ChatbotConversationRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:cleanup-chatbot-conversations',
    description: 'Nettoie les anciennes conversations du chatbot (suppression et anonymisation)',
)]
class CleanupChatbotConversationsCommand extends Command
{
    public function __construct(
        private readonly ChatbotConversationRepository $conversationRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('delete-older-than', null, InputOption::VALUE_REQUIRED, 'Nombre de jours pour la suppression (par défaut 90)', 90)
            ->addOption('anonymize-older-than', null, InputOption::VALUE_REQUIRED, 'Nombre de jours pour l\'anonymisation (par défaut 30)', 30)
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Mode simulation (aucune modification)')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Forcer l\'exécution sans confirmation');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $deleteDays = (int) $input->getOption('delete-older-than');
        $anonymizeDays = (int) $input->getOption('anonymize-older-than');
        $dryRun = (bool) $input->getOption('dry-run');
        $force = (bool) $input->getOption('force');

        if (!$force) {
            $io->warning('Cette commande va supprimer et anonymiser des données de manière irréversible.');
            $io->text(sprintf(
                'Configuration actuelle:\n' .
                '  - Suppression des conversations de plus de %d jours\n' .
                '  - Anonymisation des conversations de plus de %d jours\n' .
                '  - Mode simulation: %s',
                $deleteDays,
                $anonymizeDays,
                $dryRun ? 'OUI' : 'NON'
            ));
            
            if (!$io->confirm('Voulez-vous continuer ?', false)) {
                $io->error('Opération annulée par l\'utilisateur.');
                return Command::FAILURE;
            }
        }

        $em = $this->conversationRepository->getEntityManager();
        
        if (!$dryRun) {
            $em->getConnection()->beginTransaction();
        }

        try {
            // Anonymisation des conversations anciennes
            $io->section('Anonymisation des conversations anciennes');
            $anonymizedCount = $this->conversationRepository->anonymizeOldConversations($anonymizeDays);
            $io->text(sprintf('  Conversations anonymisées: %d', $anonymizedCount));

            // Suppression des conversations très anciennes
            $io->section('Suppression des conversations très anciennes');
            $deletedCount = $this->conversationRepository->deleteOldConversations($deleteDays);
            $io->text(sprintf('  Conversations supprimées: %d', $deletedCount));

            if (!$dryRun) {
                $em->getConnection()->commit();
                $io->success(sprintf('Nettoyage terminé: %d anonymisées, %d supprimées', $anonymizedCount, $deletedCount));
            } else {
                $io->note(sprintf('Mode simulation: %d seraient anonymisées, %d seraient supprimées', $anonymizedCount, $deletedCount));
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            if (!$dryRun) {
                $em->getConnection()->rollBack();
            }
            $io->error(sprintf('Erreur lors du nettoyage: %s', $e->getMessage()));
            return Command::FAILURE;
        }
    }
}
