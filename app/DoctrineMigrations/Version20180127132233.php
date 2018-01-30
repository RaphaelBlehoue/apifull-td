<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180127132233 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commands CHANGE status_name status_name VARCHAR(40) DEFAULT NULL');
        $this->addSql('ALTER TABLE prices CHANGE negociate is_negociate TINYINT(1) DEFAULT NULL, CHANGE actived is_actived TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commands CHANGE status_name status_name TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE prices CHANGE is_negociate negociate TINYINT(1) DEFAULT NULL, CHANGE is_actived actived TINYINT(1) NOT NULL');
    }
}
