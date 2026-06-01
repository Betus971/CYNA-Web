<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260601122153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chatbot_conversation ADD contact_message_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chatbot_conversation ADD CONSTRAINT FK_764526E894C34ABE FOREIGN KEY (contact_message_id) REFERENCES contact_message (id) ON DELETE SET NULL NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_764526E894C34ABE ON chatbot_conversation (contact_message_id)');
        $this->addSql('DROP INDEX idx_user_google_id');
        $this->addSql('DROP INDEX idx_user_email');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chatbot_conversation DROP CONSTRAINT FK_764526E894C34ABE');
        $this->addSql('DROP INDEX IDX_764526E894C34ABE');
        $this->addSql('ALTER TABLE chatbot_conversation DROP contact_message_id');
        $this->addSql('CREATE INDEX idx_user_google_id ON "user" (google_id)');
        $this->addSql('CREATE INDEX idx_user_email ON "user" (email)');
    }
}
