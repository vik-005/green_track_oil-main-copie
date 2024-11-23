<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241018112709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entre_stock CHANGE modification_count modification_count INT NOT NULL');
        $this->addSql('ALTER TABLE rapport CHANGE commentaire commentaire VARCHAR(255) DEFAULT NULL, CHANGE superieure superieure VARCHAR(255) DEFAULT NULL, CHANGE date_demande date_demande DATETIME DEFAULT NULL, CHANGE date_approbation date_approbation DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entre_stock CHANGE modification_count modification_count VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE rapport CHANGE commentaire commentaire VARCHAR(255) NOT NULL, CHANGE superieure superieure VARCHAR(255) NOT NULL, CHANGE date_demande date_demande DATETIME NOT NULL, CHANGE date_approbation date_approbation DATETIME NOT NULL');
    }
}
