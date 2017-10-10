<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171010141101 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users ADD firstname VARCHAR(255) DEFAULT NULL, ADD lastname VARCHAR(255) DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(35) DEFAULT NULL COMMENT \'(DC2Type:phone_number)\', ADD is_password_default TINYINT(1) DEFAULT NULL, ADD code_validation INT DEFAULT NULL, ADD created DATETIME NOT NULL, CHANGE is_active is_active TINYINT(1) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9444F97DD ON users (phone)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_1483A5E9444F97DD ON users');
        $this->addSql('ALTER TABLE users DROP firstname, DROP lastname, DROP email, DROP phone, DROP is_password_default, DROP code_validation, DROP created, CHANGE is_active is_active TINYINT(1) NOT NULL');
    }
}
