<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180118184022 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE stocks (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, stock_min INT NOT NULL, secure_stock INT NOT NULL, quantity INT NOT NULL, type VARCHAR(255) NOT NULL, origin VARCHAR(255) NOT NULL, created DATETIME NOT NULL, INDEX IDX_56F798054584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_56F798054584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX product_idx ON products');
        $this->addSql('CREATE INDEX product_idx ON products (name, sku)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE stocks');
        $this->addSql('DROP INDEX product_idx ON products');
        $this->addSql('CREATE INDEX product_idx ON products (name, sku)');
    }
}
