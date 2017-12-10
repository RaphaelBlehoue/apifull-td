<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171208142742 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categories CHANGE top top TINYINT(1) NOT NULL, CHANGE online online TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE departments CHANGE top top TINYINT(1) NOT NULL, CHANGE online online TINYINT(1) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categories CHANGE top top TINYINT(1) DEFAULT NULL, CHANGE online online TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE departments CHANGE top top TINYINT(1) DEFAULT NULL, CHANGE online online TINYINT(1) DEFAULT NULL');
    }
}
