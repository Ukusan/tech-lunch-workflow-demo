<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210425194942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transition (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ticket_id INTEGER NOT NULL, state_form VARCHAR(50) NOT NULL, state_to VARCHAR(50) NOT NULL, time_stamp DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_F715A75A700047D2 ON transition (ticket_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE transition');
    }
}
