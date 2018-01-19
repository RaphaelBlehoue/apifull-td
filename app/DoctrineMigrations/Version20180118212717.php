<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180118212717 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX product_idx ON products');
        $this->addSql('CREATE INDEX product_idx ON products (name, sku)');
        $this->addSql('ALTER TABLE stocks ADD stock_fn INT DEFAULT NULL, CHANGE type type TINYINT(1) NOT NULL COMMENT \'1 => entreé, 0=>sortie\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX product_idx ON products');
        $this->addSql('CREATE INDEX product_idx ON products (name, sku)');
        $this->addSql('ALTER TABLE stocks DROP stock_fn, CHANGE type type TINYINT(1) NOT NULL');
    }
}
