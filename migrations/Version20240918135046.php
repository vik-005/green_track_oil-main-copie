<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240918135046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collectes_huile CHANGE photo_bidons photo_bidons VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE demandes_prospection ADD commentaire VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collectes_huile CHANGE photo_bidons photo_bidons VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE demandes_prospection DROP commentaire');
    }
}
