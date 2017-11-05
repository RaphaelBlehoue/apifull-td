<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171105201502 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_2D5B0234F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE countries (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(10) DEFAULT NULL, UNIQUE INDEX UNIQ_5D66EBAD5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, top TINYINT(1) DEFAULT NULL, online TINYINT(1) NOT NULL, slug VARCHAR(255) DEFAULT NULL, color_code VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_CD1DE18A5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE section (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, online TINYINT(1) DEFAULT NULL, INDEX IDX_2D737AEF12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234F92F3E70 FOREIGN KEY (country_id) REFERENCES countries (id)');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEF12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE categories ADD department_id INT DEFAULT NULL, ADD top TINYINT(1) DEFAULT NULL, ADD slug VARCHAR(255) DEFAULT NULL, ADD online TINYINT(1) DEFAULT NULL, DROP code');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('CREATE INDEX IDX_3AF34668AE80F5DF ON categories (department_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234F92F3E70');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668AE80F5DF');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE countries');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE section');
        $this->addSql('DROP INDEX IDX_3AF34668AE80F5DF ON categories');
        $this->addSql('ALTER TABLE categories ADD code VARCHAR(225) NOT NULL COLLATE utf8_unicode_ci, DROP department_id, DROP top, DROP slug, DROP online');
    }
}
