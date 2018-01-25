<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180125000953 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE orders_products (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, command_id INT DEFAULT NULL, quantity INT NOT NULL, promo_status TINYINT(1) NOT NULL, INDEX IDX_749C879C4584665A (product_id), INDEX IDX_749C879C33E1689A (command_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT FK_749C879C4584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT FK_749C879C33E1689A FOREIGN KEY (command_id) REFERENCES commands (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE order_product');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE order_product (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, command_id INT DEFAULT NULL, quantity INT NOT NULL, promo_status TINYINT(1) NOT NULL, INDEX IDX_2530ADE64584665A (product_id), INDEX IDX_2530ADE633E1689A (command_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE633E1689A FOREIGN KEY (command_id) REFERENCES commands (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE64584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE orders_products');
    }
}
