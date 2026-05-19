<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260519104500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Normalize Stripe payment intent unique index name.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER INDEX IF EXISTS uniq_f52993989245c18d RENAME TO UNIQ_F5299398FC72F97E');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER INDEX IF EXISTS uniq_f5299398fc72f97e RENAME TO UNIQ_F52993989245C18D');
    }
}
