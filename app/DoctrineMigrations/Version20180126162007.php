<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180126162007 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE promotions (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, code VARCHAR(6) DEFAULT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, percent INT NOT NULL, actived TINYINT(1) NOT NULL, created DATETIME NOT NULL, INDEX IDX_EA1B30344584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promotions ADD CONSTRAINT FK_EA1B30344584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE promotion');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, code VARCHAR(6) DEFAULT NULL COLLATE utf8_unicode_ci, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, content LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, percent INT NOT NULL, actived TINYINT(1) NOT NULL, created DATETIME NOT NULL, INDEX IDX_C11D7DD14584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD14584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE promotions');
    }
}
