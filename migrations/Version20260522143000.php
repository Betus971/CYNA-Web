<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260522143000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Google OAuth identifier and lookup indexes to users.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD google_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_USER_EMAIL ON "user" (email)');
        $this->addSql('CREATE INDEX IDX_USER_GOOGLE_ID ON "user" (google_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IDX_USER_GOOGLE_ID');
        $this->addSql('DROP INDEX IDX_USER_EMAIL');
        $this->addSql('ALTER TABLE "user" DROP google_id');
    }
}
