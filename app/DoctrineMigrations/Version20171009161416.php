<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171009161416 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE brands (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(225) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE companies (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(255) NOT NULL, bank VARCHAR(255) NOT NULL, taxpayer_account VARCHAR(255) DEFAULT NULL, tax_center VARCHAR(255) DEFAULT NULL, city VARCHAR(255) NOT NULL, address LONGTEXT NOT NULL, street VARCHAR(255) NOT NULL, regime VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, represent VARCHAR(255) NOT NULL, phone_two VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, fax VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conditions (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, additional_informations LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, address LONGTEXT DEFAULT NULL, delegate VARCHAR(255) NOT NULL, created DATE NOT NULL, email_company VARCHAR(255) DEFAULT NULL, email_delegate VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_62534E21989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deliveryvouchers (id INT AUTO_INCREMENT NOT NULL, quotation_id INT DEFAULT NULL, created DATETIME NOT NULL, delivery DATE NOT NULL, status INT NOT NULL, reference VARCHAR(225) NOT NULL, UNIQUE INDEX UNIQ_ED4359EBB4EA4E60 (quotation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventories (id INT AUTO_INCREMENT NOT NULL, product_id VARCHAR(36) DEFAULT NULL, warehouse_id INT DEFAULT NULL, movement_id INT DEFAULT NULL, reference VARCHAR(8) NOT NULL COMMENT \'référence de l\'\'inventaire\', sku VARCHAR(8) NOT NULL COMMENT \'faire référence au produit relié a ce movement\', code_movement INT NOT NULL COMMENT \'correspond au status du movement du stock\', referrer VARCHAR(225) DEFAULT NULL COMMENT \'reference à la quelle ce movement est relié\', quantity INT NOT NULL, created DATE NOT NULL, updated DATE NOT NULL, INDEX IDX_936C863D4584665A (product_id), INDEX IDX_936C863D5080ECDE (warehouse_id), INDEX IDX_936C863D229E70A7 (movement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locations (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, longitude VARCHAR(255) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, created DATE NOT NULL, UNIQUE INDEX UNIQ_17E64ABA989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movements (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persons (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id VARCHAR(36) NOT NULL, unit_id INT DEFAULT NULL, category_id INT DEFAULT NULL, brand_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, reference VARCHAR(255) NOT NULL, buying_price BIGINT DEFAULT NULL COMMENT \'Prix d\'\'achat du produits\', rental_price BIGINT DEFAULT NULL COMMENT \'Prix de location de la machandise\', cost BIGINT DEFAULT NULL COMMENT \'Cout du produit de type service\', coefficient DOUBLE PRECISION DEFAULT NULL COMMENT \'coefficient de multiplication du produit pour la vente\', stock_alert INT DEFAULT NULL COMMENT \'stock minimum du produit\', libelle LONGTEXT DEFAULT NULL, type INT DEFAULT NULL COMMENT \'Type de service : soit produit ou service\', created DATE NOT NULL, UNIQUE INDEX UNIQ_B3BA5A5A989D9B62 (slug), UNIQUE INDEX UNIQ_B3BA5A5AAEA34913 (reference), INDEX IDX_B3BA5A5AF8BD700D (unit_id), INDEX IDX_B3BA5A5A12469DE2 (category_id), INDEX IDX_B3BA5A5A44F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quotations (id INT AUTO_INCREMENT NOT NULL, service_id INT DEFAULT NULL, condition_id INT DEFAULT NULL, user_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', customer_id INT DEFAULT NULL, company_id INT DEFAULT NULL, reference VARCHAR(255) NOT NULL, reference_view VARCHAR(255) NOT NULL, created DATE NOT NULL, updated DATE NOT NULL, status INT NOT NULL, sum_letter LONGTEXT DEFAULT NULL, local VARCHAR(225) DEFAULT NULL COMMENT \'Localisation du devis de location\', start DATE DEFAULT NULL COMMENT \'Date de debut de la location\', end DATE DEFAULT NULL COMMENT \'Date de fin de la location\', subject LONGTEXT DEFAULT NULL, INDEX IDX_A9F48EAEED5CA9E6 (service_id), INDEX IDX_A9F48EAE887793B6 (condition_id), INDEX IDX_A9F48EAEA76ED395 (user_id), INDEX IDX_A9F48EAE9395C3F3 (customer_id), INDEX IDX_A9F48EAE979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quotations_products (id INT AUTO_INCREMENT NOT NULL, quotation_id INT DEFAULT NULL, product_id VARCHAR(36) DEFAULT NULL, quantity INT NOT NULL, price BIGINT NOT NULL, discount DOUBLE PRECISION DEFAULT NULL, amount_tax BIGINT NOT NULL, created DATE NOT NULL, duration INT DEFAULT NULL COMMENT \'information à renseigné si le devis est de type location\', INDEX IDX_598F8F40B4EA4E60 (quotation_id), INDEX IDX_598F8F404584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE services (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE units (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', type_id INT DEFAULT NULL, username VARCHAR(25) NOT NULL, is_active TINYINT(1) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), INDEX IDX_1483A5E9C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouses (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, created DATE NOT NULL, UNIQUE INDEX UNIQ_AFE9C2B7989D9B62 (slug), INDEX IDX_AFE9C2B764D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE deliveryvouchers ADD CONSTRAINT FK_ED4359EBB4EA4E60 FOREIGN KEY (quotation_id) REFERENCES quotations (id)');
        $this->addSql('ALTER TABLE inventories ADD CONSTRAINT FK_936C863D4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE inventories ADD CONSTRAINT FK_936C863D5080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouses (id)');
        $this->addSql('ALTER TABLE inventories ADD CONSTRAINT FK_936C863D229E70A7 FOREIGN KEY (movement_id) REFERENCES movements (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AF8BD700D FOREIGN KEY (unit_id) REFERENCES units (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A44F5D008 FOREIGN KEY (brand_id) REFERENCES brands (id)');
        $this->addSql('ALTER TABLE quotations ADD CONSTRAINT FK_A9F48EAEED5CA9E6 FOREIGN KEY (service_id) REFERENCES services (id)');
        $this->addSql('ALTER TABLE quotations ADD CONSTRAINT FK_A9F48EAE887793B6 FOREIGN KEY (condition_id) REFERENCES conditions (id)');
        $this->addSql('ALTER TABLE quotations ADD CONSTRAINT FK_A9F48EAEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE quotations ADD CONSTRAINT FK_A9F48EAE9395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
        $this->addSql('ALTER TABLE quotations ADD CONSTRAINT FK_A9F48EAE979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE quotations_products ADD CONSTRAINT FK_598F8F40B4EA4E60 FOREIGN KEY (quotation_id) REFERENCES quotations (id)');
        $this->addSql('ALTER TABLE quotations_products ADD CONSTRAINT FK_598F8F404584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9C54C8C93 FOREIGN KEY (type_id) REFERENCES types (id)');
        $this->addSql('ALTER TABLE warehouses ADD CONSTRAINT FK_AFE9C2B764D218E FOREIGN KEY (location_id) REFERENCES locations (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A44F5D008');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A12469DE2');
        $this->addSql('ALTER TABLE quotations DROP FOREIGN KEY FK_A9F48EAE979B1AD6');
        $this->addSql('ALTER TABLE quotations DROP FOREIGN KEY FK_A9F48EAE887793B6');
        $this->addSql('ALTER TABLE quotations DROP FOREIGN KEY FK_A9F48EAE9395C3F3');
        $this->addSql('ALTER TABLE warehouses DROP FOREIGN KEY FK_AFE9C2B764D218E');
        $this->addSql('ALTER TABLE inventories DROP FOREIGN KEY FK_936C863D229E70A7');
        $this->addSql('ALTER TABLE inventories DROP FOREIGN KEY FK_936C863D4584665A');
        $this->addSql('ALTER TABLE quotations_products DROP FOREIGN KEY FK_598F8F404584665A');
        $this->addSql('ALTER TABLE deliveryvouchers DROP FOREIGN KEY FK_ED4359EBB4EA4E60');
        $this->addSql('ALTER TABLE quotations_products DROP FOREIGN KEY FK_598F8F40B4EA4E60');
        $this->addSql('ALTER TABLE quotations DROP FOREIGN KEY FK_A9F48EAEED5CA9E6');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9C54C8C93');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5AF8BD700D');
        $this->addSql('ALTER TABLE quotations DROP FOREIGN KEY FK_A9F48EAEA76ED395');
        $this->addSql('ALTER TABLE inventories DROP FOREIGN KEY FK_936C863D5080ECDE');
        $this->addSql('DROP TABLE brands');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE companies');
        $this->addSql('DROP TABLE conditions');
        $this->addSql('DROP TABLE customers');
        $this->addSql('DROP TABLE deliveryvouchers');
        $this->addSql('DROP TABLE inventories');
        $this->addSql('DROP TABLE locations');
        $this->addSql('DROP TABLE movements');
        $this->addSql('DROP TABLE persons');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE quotations');
        $this->addSql('DROP TABLE quotations_products');
        $this->addSql('DROP TABLE services');
        $this->addSql('DROP TABLE types');
        $this->addSql('DROP TABLE units');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE warehouses');
    }
}
