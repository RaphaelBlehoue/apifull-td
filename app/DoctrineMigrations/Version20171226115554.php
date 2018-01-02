<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171226115554 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_B3BA5A5AAEA34913 ON products');
        $this->addSql('ALTER TABLE products ADD sku VARCHAR(255) DEFAULT NULL, DROP reference, CHANGE libelle content LONGTEXT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3BA5A5AF9038C4 ON products (sku)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_B3BA5A5AF9038C4 ON products');
        $this->addSql('ALTER TABLE products ADD reference VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP sku, CHANGE content libelle LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3BA5A5AAEA34913 ON products (reference)');
    }
}
