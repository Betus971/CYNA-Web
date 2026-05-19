<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260519103000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Stripe payment tracking fields to orders.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "order" ADD stripe_payment_intent_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD stripe_payment_status VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD payment_failure_reason VARCHAR(500) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F5299398FC72F97E ON "order" (stripe_payment_intent_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_F5299398FC72F97E');
        $this->addSql('ALTER TABLE "order" DROP stripe_payment_intent_id');
        $this->addSql('ALTER TABLE "order" DROP stripe_payment_status');
        $this->addSql('ALTER TABLE "order" DROP payment_failure_reason');
    }
}
