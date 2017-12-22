<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171222124549 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE colors (id INT AUTO_INCREMENT NOT NULL, color VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_C2BEC39F665648E9 (color), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id VARCHAR(36) NOT NULL, section_id INT DEFAULT NULL, brand_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, reference VARCHAR(255) NOT NULL, libelle LONGTEXT DEFAULT NULL, created DATE NOT NULL, UNIQUE INDEX UNIQ_B3BA5A5A989D9B62 (slug), UNIQUE INDEX UNIQ_B3BA5A5AAEA34913 (reference), INDEX IDX_B3BA5A5AD823E37A (section_id), INDEX IDX_B3BA5A5A44F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products_colors (product_id VARCHAR(36) NOT NULL, color_id INT NOT NULL, INDEX IDX_448D48B54584665A (product_id), INDEX IDX_448D48B57ADA1FB5 (color_id), PRIMARY KEY(product_id, color_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products_sizes (product_id VARCHAR(36) NOT NULL, size_id INT NOT NULL, INDEX IDX_56C779B94584665A (product_id), INDEX IDX_56C779B9498DA827 (size_id), PRIMARY KEY(product_id, size_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sizes (id INT AUTO_INCREMENT NOT NULL, size VARCHAR(6) NOT NULL, UNIQUE INDEX UNIQ_B69E8769F7C0246A (size), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AD823E37A FOREIGN KEY (section_id) REFERENCES sections (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A44F5D008 FOREIGN KEY (brand_id) REFERENCES brands (id)');
        $this->addSql('ALTER TABLE products_colors ADD CONSTRAINT FK_448D48B54584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_colors ADD CONSTRAINT FK_448D48B57ADA1FB5 FOREIGN KEY (color_id) REFERENCES colors (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_sizes ADD CONSTRAINT FK_56C779B94584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_sizes ADD CONSTRAINT FK_56C779B9498DA827 FOREIGN KEY (size_id) REFERENCES sizes (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE products_colors DROP FOREIGN KEY FK_448D48B57ADA1FB5');
        $this->addSql('ALTER TABLE products_colors DROP FOREIGN KEY FK_448D48B54584665A');
        $this->addSql('ALTER TABLE products_sizes DROP FOREIGN KEY FK_56C779B94584665A');
        $this->addSql('ALTER TABLE products_sizes DROP FOREIGN KEY FK_56C779B9498DA827');
        $this->addSql('DROP TABLE colors');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE products_colors');
        $this->addSql('DROP TABLE products_sizes');
        $this->addSql('DROP TABLE sizes');
    }
}
