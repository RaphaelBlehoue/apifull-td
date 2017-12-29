<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171226113914 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_7EA244345E237E06 ON brands (name)');
        $this->addSql('ALTER TABLE products ADD store_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AB092A811 FOREIGN KEY (store_id) REFERENCES stores (id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5AB092A811 ON products (store_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_7EA244345E237E06 ON brands');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5AB092A811');
        $this->addSql('DROP INDEX IDX_B3BA5A5AB092A811 ON products');
        $this->addSql('ALTER TABLE products DROP store_id');
    }
}
