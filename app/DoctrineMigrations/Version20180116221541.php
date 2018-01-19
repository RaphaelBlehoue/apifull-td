<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180116221541 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE medias DROP FOREIGN KEY FK_12D2AF814584665A');
        $this->addSql('ALTER TABLE medias ADD CONSTRAINT FK_12D2AF814584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prices DROP FOREIGN KEY FK_E4CB6D594584665A');
        $this->addSql('ALTER TABLE prices ADD CONSTRAINT FK_E4CB6D594584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE medias DROP FOREIGN KEY FK_12D2AF814584665A');
        $this->addSql('ALTER TABLE medias ADD CONSTRAINT FK_12D2AF814584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE prices DROP FOREIGN KEY FK_E4CB6D594584665A');
        $this->addSql('ALTER TABLE prices ADD CONSTRAINT FK_E4CB6D594584665A FOREIGN KEY (product_id) REFERENCES products (id)');
    }
}
