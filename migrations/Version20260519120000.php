<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260519120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add email 2FA and login notification settings to users.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD email_two_factor_enabled BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD email_two_factor_code_hash VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD email_two_factor_code_expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD login_notification_enabled BOOLEAN DEFAULT false NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" DROP email_two_factor_enabled');
        $this->addSql('ALTER TABLE "user" DROP email_two_factor_code_hash');
        $this->addSql('ALTER TABLE "user" DROP email_two_factor_code_expires_at');
        $this->addSql('ALTER TABLE "user" DROP login_notification_enabled');
    }
}
